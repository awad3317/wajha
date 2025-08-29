<?php

namespace App\Http\Controllers\API;


use Exception;
use Illuminate\Http\Request;
use App\Services\CouponService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Services\FirebaseService;
use App\Http\Controllers\Controller;
use App\Services\BookingStatusService;
use App\Repositories\bookingRepository;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewBookingNotification;
use App\Repositories\EstablishmentRepository;

class BookingController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private bookingRepository $bookingRepository,private FirebaseService $firebaseService,private CouponService $couponService,private EstablishmentRepository $EstablishmentRepository,private BookingStatusService $bookingStatusService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $fields = $request->validate([
            'establishment_id' => ['required',Rule::exists('establishments','id')],
            'price_package_id' => ['required',
                    Rule::exists('price_packages', 'id')->where(function ($query) use ($request) {
                    $query->where('establishment_id', $request->establishment_id);
                })
            ],
            'booking_date' =>['required','date','after_or_equal:now'],
            'coupon_code' => ['nullable','string'],
        ]);

        try {
            $user_id = auth('sanctum')->id();
            $fields['user_id'] = $user_id;

            $existingBooking = $this->bookingRepository->findExistingBooking($user_id,$fields['establishment_id']);

            if ($existingBooking) {
                return ApiResponseClass::sendError('لديك بالفعل حجز نشط في هذه المنشأة ولا يمكنك حجز مرة أخرى.', 400);
            }

            $establishment = $this->EstablishmentRepository->getById($fields['establishment_id']);
            if (!empty($fields['coupon_code'])) {
                $coupon = $this->couponService->getCouponByCode($fields['coupon_code']);
                if ($coupon) {
                    if (!$this->couponService->isCouponActive($coupon)) {
                       return ApiResponseClass::sendError('عذراً، هذا الكوبون غير مفعل حالياً. يرجى استخدام كوبون آخر.', 400);    
                    }
                    if (!$this->couponService->isWithinValidDates($coupon)) {
                        $now = now();
                        if ($now->lt($coupon->start_date)) {
                            return ApiResponseClass::sendError('عذراً، هذا الكوبون سيصبح فعالاً في تاريخ: '.$coupon->start_date->format('Y-m-d'), 400);
                        } else {
                            return ApiResponseClass::sendError('عذراً، انتهت صلاحية هذا الكوبون في تاريخ: '.$coupon->end_date->format('Y-m-d'), 400);
                        }
                    }
                    if (!$this->couponService->hasUsageLimitAvailable($coupon)) {
                        return ApiResponseClass::sendError('تم الوصول إلى الحد الأقصى لاستخدام هذا الكوبون.', 400);
                    }
                    if (!$this->couponService->isApplicableToEstablishment($coupon, $establishment)) {
                        return ApiResponseClass::sendError('عذراً، هذا الكوبون غير صالح للاستخدام مع هذه المنشأة.', 400);
                    }
                    $fields['discount_amount'] = $this->couponService->calculateDiscountAmount(
                     $coupon, 
                     $establishment->pricePackages()->findOrFail($fields['price_package_id'])->price
                );
                $fields['coupon_id']=$coupon->id;
                } else {
                   return ApiResponseClass::sendError('كود الكوبون الذي أدخلته غير صحيح. يرجى التحقق والمحاولة مرة أخرى.', 400);
                }
            }
            
            $booking = $this->bookingRepository->store($fields);
             if (!empty($fields['coupon_code'])) {
            $this->couponService->recordCouponUse([
                'coupon_id' => $coupon->id,
                'user_id' => $user_id,
                'discount_amount' => $booking->discount_amount,
            ], $booking->id);
        }
            $owner = $establishment->owner;

            // send database notification to the owner
            $owner->notify(new NewBookingNotification($booking,'حجز جديد','حجز جديد في منشأتك: ' . $booking->establishment->name,'owner'));

            // send to FCM notification to the owner
            $title = "حجز جديد في منشأتك";
            $body = "تم حجز {$establishment->name} من قبل عميل جديد. تاريخ الحجز: " . $booking->booking_date;
            $data = [
                'type' => 'new_booking',
                'booking_id' => $booking->id,
                'establishment_id' => $establishment->id,
                'user_id' => $user_id,
            ];
            if ($owner->device_token) {
                $this->firebaseService->sendNotification($owner->device_token, $title, $body, $data);
            }
            
            return ApiResponseClass::sendResponse($booking, 'Booking saving successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error saving booking: ' . $e->getMessage());
        }
    }

    public function markAsWaitingPayment(Request $request)
    {
        $fields = $request->validate([
          'booking_id'=>['required',Rule::exists('bookings','id')]
        ]);
        try {
            $booking= $this->bookingRepository->getById($fields['booking_id']);
            $bookingStatus = $this->bookingStatusService->markAsWaitingPayment($booking);
            $user = $booking->user;
            $establishment = $booking->establishment;

            $user->notify(new NewBookingNotification($booking,'بانتظار الدفع',"حجزك في {$booking->establishment->name} بانتظار الدفع",'customer'));

            $title = "بانتظار الدفع";
            $body = "حجزك في {$establishment->name} بانتظار الدفع. الرجاء إتمام الدفع قبل تاريخ الحجز: " . $booking->booking_date;
        
            $data = [
                'type' => 'بانتظار الدفع',
                'booking_id' => $booking->id,
                'establishment_id' => $establishment->id,
                'user_id' => $user->id,
            ];
        
            if ($user->device_token) {
                $this->firebaseService->sendNotification($user->device_token, $title, $body, $data);
            }
            return ApiResponseClass::sendResponse($booking,'تم تحديث حالة الحجز إلى "بانتظار الدفع" بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ أثناء تحديث حالة الحجز: ' . $e->getMessage(), [], 500);
        }
        
    }

    public function markAsPaid(Request $request)
{
    $fields = $request->validate([
        'booking_id' => ['required', Rule::exists('bookings', 'id')],
        'receipt_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
    ]);

    try {
        $booking = $this->bookingRepository->getById($fields['booking_id']);
        $currentReceiptImage = $booking->receipt_image;
        
        if ($request->hasFile('receipt_image')) {
            if ($currentReceiptImage && Storage::disk('private')->exists($currentReceiptImage)) {
                Storage::disk('private')->delete($currentReceiptImage);
            }
            $receiptImage = $request->file('receipt_image')->storeAs(
                'payment-receipts', 
                'receipt_' . time() . '_' . uniqid() . '.' . $request->file('receipt_image')->extension(),
                'private'
            );
        } else {
            return ApiResponseClass::sendError('صورة الإيصال مطلوبة', [], 400);
        }

        $booking = $this->bookingStatusService->markAsPaid($booking, $receiptImage);
        $user = $booking->user;
        $establishment = $booking->establishment;

        $user->notify(new NewBookingNotification(
            $booking, 
            'تم الدفع', 
            "تم تأكيد الدفع لحجزك في {$establishment->name}", 
            'customer'
        ));

        $establishmentOwner = $establishment->user;
        $establishmentOwner->notify(new NewBookingNotification(
            $booking, 
            'تم دفع حجز جديد', 
            "تم دفع حجز جديد في {$establishment->name} من قبل {$user->name}", 
            'owner'
        ));

        $title = "تم الدفع بنجاح";
        $body = "تم تأكيد الدفع لحجزك في {$establishment->name}. رقم الحجز: {$booking->id}";
    
        $data = [
            'type' => 'تم الدفع',
            'booking_id' => $booking->id,
            'establishment_id' => $establishment->id,
            'user_id' => $user->id,
        ];
    
        if ($user->device_token) {
            $this->firebaseService->sendNotification($user->device_token, $title, $body, $data);
        }

        if ($establishmentOwner->device_token) {
            $ownerTitle = "حجز جديد مدفوع";
            $ownerBody = "حجز جديد مدفوع في {$establishment->name} من قبل {$user->name}";
            $this->firebaseService->sendNotification($establishmentOwner->device_token, $ownerTitle, $ownerBody, $data);
        }

        return ApiResponseClass::sendResponse($booking, 'تم تحديث حالة الحجز إلى "تم الدفع" بنجاح');

    } catch (Exception $e) {
        if (isset($receiptImage) && Storage::disk('private')->exists($receiptImage)) {
            Storage::disk('private')->delete($receiptImage);
        }
        return ApiResponseClass::sendError('حدث خطأ أثناء تأكيد الدفع: ' . $e->getMessage(), [], 500);
    }
}
    public function confirmBooking(Request $request)
{
    $fields = $request->validate([
        'booking_id' => ['required', Rule::exists('bookings', 'id')],
    ]);

    try {
        $booking = $this->bookingRepository->getById($fields['booking_id']);
        
        $booking = $this->bookingStatusService->confirmBooking($booking);
        $user = $booking->user;
        $establishment = $booking->establishment;

        if (auth('sanctum')->id() != $establishment->owner_id) {
            return ApiResponseClass::sendError('غير مصرح لك بتأكيد هذا الحجز', [], 403);
        }
        $user->notify(new NewBookingNotification(
            $booking, 
            'تم تأكيد الحجز', 
            "تم تأكيد حجزك في {$establishment->name}", 
            'customer'
        ));

        $establishmentOwner = $establishment->user;
        $establishmentOwner->notify(new NewBookingNotification(
            $booking, 
            'تم تأكيد حجز', 
            "تم تأكيد حجز في {$establishment->name} من قبل {$user->name}", 
            'owner'
        ));

        $title = "تم تأكيد الحجز";
        $body = "تم تأكيد حجزك في {$establishment->name}. رقم الحجز: {$booking->id}";
    
        $data = [
            'type' => 'تم التأكيد',
            'booking_id' => $booking->id,
            'establishment_id' => $establishment->id,
            'user_id' => $user->id,
        ];
    
        if ($user->device_token) {
            $this->firebaseService->sendNotification($user->device_token, $title, $body, $data);
        }

        if ($establishmentOwner->device_token) {
            $ownerTitle = "تم تأكيد حجز";
            $ownerBody = "تم تأكيد حجز في {$establishment->name} من قبل {$user->name}";
            $this->firebaseService->sendNotification($establishmentOwner->device_token, $ownerTitle, $ownerBody, $data);
        }

        return ApiResponseClass::sendResponse($booking, 'تم تأكيد الحجز بنجاح');

    } catch (Exception $e) {
        return ApiResponseClass::sendError('حدث خطأ أثناء تأكيد الحجز: ' . $e->getMessage(), [], 500);
    }
}

public function cancelledBooking(Request $request)
{
    $fields = $request->validate([
        'booking_id' => ['required', Rule::exists('bookings', 'id')],
        'cancellation_reason' => ['nullable', 'string', 'max:500'],
    ]);

    try {
        $booking = $this->bookingRepository->getById($fields['booking_id']);
        $user = auth('sanctum')->user();
        $establishment = $booking->establishment;

        if ($user->id !== $booking->user_id && $user->id !== $establishment->owner_id) {
            return ApiResponseClass::sendError('غير مصرح لك بإلغاء هذا الحجز', [], 403);
        }

        $booking = $this->bookingStatusService->cancelledBooking($booking, $fields['cancellation_reason'] ?? null);

        if($user->id == $booking->user_id){
            $establishmentOwner = $establishment->user;
            $establishmentOwner->notify(new NewBookingNotification(
            $booking, 
            'تم إلغاء الحجز', 
            "تم إلغاء الحجز في {$establishment->name} من قبل العميل",
            'owner'
            ));
            $title = "تم إلغاء الحجز";
            $body = "تم إلغاء الحجز في {$establishment->name} من قبل العميل";
        
            if (!empty($fields['cancellation_reason'])) {
                $body .= ". السبب: " . $fields['cancellation_reason'];
            }
            $data = [
                'type' => 'تم الإلغاء',
                'booking_id' => $booking->id,
                'establishment_id' => $establishment->id,
                'user_id' => $booking->user_id,
            ];
            if ($establishmentOwner->device_token) {
            $this->firebaseService->sendNotification(
                $establishmentOwner->device_token, 
                $title, 
                $body, 
                $data
            );
        }
        } else {
           $customer = $booking->user;
           $customer->notify(new NewBookingNotification(
            $booking, 
            'تم إلغاء الحجز', 
            "تم إلغاء حجزك في {$establishment->name} من قبل مالك المنشأة",
            'customer'
            ));
            $title = "تم إلغاء الحجز";
            $body = "تم إلغاء حجزك في {$establishment->name} من قبل مالك المنشأة";
        
            if (!empty($fields['cancellation_reason'])) {
                $body .= ". السبب: " . $fields['cancellation_reason'];
            }
            $data = [
                'type' => 'تم الإلغاء',
                'booking_id' => $booking->id,
                'establishment_id' => $establishment->id,
                'user_id' => $booking->user_id,
            ];
            if ($customer->device_token) {
            $this->firebaseService->sendNotification(
                $customer->device_token, 
                $title, 
                $body, 
                $data
            );
            }
        }
        return ApiResponseClass::sendResponse(
            $booking, 
            'تم إلغاء الحجز بنجاح'
        );

    } catch (Exception $e) {
        return ApiResponseClass::sendError(
            'حدث خطأ أثناء إلغاء الحجز: ' . $e->getMessage(), 
            [], 
            500
        );
    }
}

    public function revertBookingStatus(Request $request)
{
    $fields = $request->validate([
        'booking_id' => ['required', Rule::exists('bookings', 'id')],
        'target_status' => ['required', Rule::in(['pending', 'waiting_payment', 'paid', 'confirmed'])]
    ]);

    try {
        $booking = $this->bookingRepository->getById($fields['booking_id']);
        $user = auth('sanctum')->user();
        $establishment = $booking->establishment;

        if ($user->id !== $establishment->owner_id) {
            return ApiResponseClass::sendError('غير مصرح لك بالتراجع عن حالة هذا الحجز', [], 403);
        }

        $booking = $this->bookingStatusService->revertBookingStatus($booking, $fields['target_status']);

        $customer = $booking->user;

        $customer->notify(new NewBookingNotification(
            $booking, 
            'تم تعديل حالة الحجز', 
            "تم تعديل حالة حجزك في {$establishment->name} إلى: " . $this->getArabicStatus($fields['target_status']),
            'customer'
        ));
            $title = "تم تعديل حالة الحجز";
        $body = "تم تعديل حالة حجزك في {$establishment->name} إلى: " . $this->getArabicStatus($fields['target_status']);
    
        $data = [
            'type' => 'تعديل الحالة',
            'booking_id' => $booking->id,
            'establishment_id' => $establishment->id,
            'user_id' => $customer->id,
            'old_status' => $booking->status,
            'new_status' => $fields['target_status']
        ];
    
        if ($customer->device_token) {
            $this->firebaseService->sendNotification($customer->device_token, $title, $body, $data);
        }
        return ApiResponseClass::sendResponse(
            $booking, 
            'تم التراجع عن حالة الحجز بنجاح إلى "' . $this->getArabicStatus($fields['target_status']) . '"'
        );
    } catch (Exception $e) {
        return ApiResponseClass::sendError(
            'حدث خطأ أثناء التراجع عن حالة الحجز: ' . $e->getMessage(), 
            [], 
            500
        );
    }
}
private function getArabicStatus($status)
{
    $statusMap = [
        'pending' => 'قيد الانتظار',
        'waiting_payment' => 'بانتظار الدفع',
        'paid' => 'تم الدفع',
        'confirmed' => 'تم التأكيد',
        'cancelled' => 'ملغي',
        'completed' => 'مكتمل'
    ];

    return $statusMap[$status] ?? $status;
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $booking=$this->bookingRepository->getById($id);
            return ApiResponseClass::sendResponse($booking,'data getted successfully');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error returned establishment: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
