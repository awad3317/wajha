<?php

namespace App\Http\Controllers\API;


use Exception;
use App\Livewire\Booking;
use Illuminate\Http\Request;
use App\Models\Establishment;
use App\Services\ImageService;
use App\Services\CouponService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\BookingStatusService;
use App\Repositories\bookingRepository;
use Illuminate\Support\Carbon;
use App\Notifications\BookingModifiedNotification;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewBookingNotification;
use App\Repositories\EstablishmentRepository;

class BookingController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private bookingRepository $bookingRepository,private FirebaseService $firebaseService,private CouponService $couponService,private EstablishmentRepository $EstablishmentRepository,private BookingStatusService $bookingStatusService,private ImageService $ImageService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = auth('sanctum')->user();
            if($user->user_type == 'owner'){
               $bookings = $this->bookingRepository->indexForOwner($user);
                return ApiResponseClass::sendResponse($bookings, 'تم جلب البيانات بنجاح');
            }
            $bookings = $this->bookingRepository->index($user->id);
            return ApiResponseClass::sendResponse($bookings, 'تم جلب البيانات بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في جلب بيانات الحجوزات: ' . $e->getMessage());
        }
      
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
                    $query->where('establishment_id', $request->establishment_id);})],
            'booking_date' =>['required','date','after_or_equal:now'],
            'coupon_code' => ['nullable','string'],
        ],[
            'establishment_id.required' => 'حقل المعرف الخاص بالمنشأة مطلوب.',
            'establishment_id.exists' => 'معرف المنشأة المحدد غير صالح.',
            'price_package_id.required' => 'حقل المعرف الخاص بباقة السعر مطلوب.',
            'price_package_id.exists' => 'معرف باقة السعر المحدد غير صالح.',
            'booking_date.required' => 'حقل تاريخ الحجز مطلوب.',
            'booking_date.date' => 'تاريخ الحجز غير صالح.',
            'booking_date.after_or_equal' => 'يجب أن يكون تاريخ الحجز في الوقت الحالي أو بعده.',
            'coupon_code.string' => 'يجب أن يكون رمز الكوبون نصًا.',
        ]);

        try {
            $user_id = auth('sanctum')->id();
            $fields['user_id'] = $user_id;

            $existingBooking = $this->bookingRepository->findExistingBooking($user_id,$fields['establishment_id']);

            if ($existingBooking) {
                return ApiResponseClass::sendError(' يجب تأكيد الحجز السابق لديك بالفعل حجز نشط في هذه المنشأة ولا يمكنك حجز مرة أخرى.', 400);
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

            $owner->notify(new NewBookingNotification($booking,'حجز جديد','حجز جديد في منشأتك: ' . $booking->establishment->name,'owner'));

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
            
            return ApiResponseClass::sendResponse($booking, 'تم حفظ الحجز بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في حفظ الحجز: ' . $e->getMessage());
        }
    }

    public function markAsWaitingPayment(Request $request)
    {
        $fields = $request->validate([
          'booking_id'=>['required',Rule::exists('bookings','id')]
        ]);
        try {
            $booking= $this->bookingRepository->getById($fields['booking_id']);
            $booking = $this->bookingStatusService->markAsWaitingPayment($booking);
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
        'receipt_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5000']
    ]);

    try {
        $booking = $this->bookingRepository->getById($fields['booking_id']);
        
        if (!$booking) {
            return ApiResponseClass::sendError('الحجز غير موجود', [], 404);
        }

        
        if ($request->hasFile('receipt_image')) {
            $image = $request->file('receipt_image');
            $imagePath = $this->ImageService->saveImage($image, 'booking-receipts');
            $receiptImage = $imagePath;
        } else {
            return ApiResponseClass::sendError('صورة الإيصال مطلوبة', [], 400);
        }

        $updatedBooking = $this->bookingStatusService->markAsPaid($booking, $receiptImage);
        
        if (!$updatedBooking) {
            return ApiResponseClass::sendError('فشل في تحديث حالة الدفع', [], 500);
        }

        $user = $booking->user;
        $establishment = $booking->establishment;

        if ($user) {
            $user->notify(new NewBookingNotification(
                $booking, 
                'تم الدفع', 
                "تم تأكيد الدفع لحجزك في {$establishment->name}", 
                'customer'
            ));
        }

        if ($establishment && $establishment->owner) {
            $establishmentOwner = $establishment->owner;
            $establishmentOwner->notify(new NewBookingNotification(
                $booking, 
                'تم دفع حجز جديد', 
                "تم دفع حجز جديد في {$establishment->name} من قبل {$user->name}", 
                'owner'
            ));
        }

        return ApiResponseClass::sendResponse($updatedBooking, 'تم تحديث حالة الحجز إلى "تم الدفع" بنجاح');

    } catch (Exception $e) {
        Log::error('خطأ في markAsPaid: ' . $e->getMessage());
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
        
        
        $user = $booking->user;
        $establishment = $booking->establishment;

        if (auth('sanctum')->id() != $establishment->owner_id) {
            return ApiResponseClass::sendError('غير مصرح لك بتأكيد هذا الحجز', [], 403);
        }
        $booking = $this->bookingStatusService->confirmBooking($booking);
        $user->notify(new NewBookingNotification(
            $booking, 
            'تم تأكيد الحجز', 
            "تم تأكيد حجزك في {$establishment->name}", 
            'customer'
        ));

        $establishmentOwner = $establishment->owner;
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

    public function completeBooking(Request $request)
    {
        $fields = $request->validate([
            'booking_id' => ['required', Rule::exists('bookings', 'id')],
        ]);

    try {
        $booking = $this->bookingRepository->getById($fields['booking_id']);
        
        $user = $booking->user;
        $establishment = $booking->establishment;

        if (auth('sanctum')->id() != $establishment->owner_id) {
            return ApiResponseClass::sendError('غير مصرح لك بإكمال هذا الحجز', [], 403);
        }

        $booking = $this->bookingStatusService->completeBooking($booking);

        // $user->notify(new NewBookingNotification(
        //     $booking, 
        //     'completed', 
        //     "تم إكمال حجزك في {$establishment->name}", 
        //     'customer'
        // ));

        // $establishmentOwner = $establishment->owner;
        // $establishmentOwner->notify(new NewBookingNotification(
        //     $booking, 
        //     'completed', 
        //     "تم إكمال حجز في {$establishment->name} للعميل {$user->name}", 
        //     'owner'
        // ));

        $title = "تم إكمال الحجز بنجاح";
        $body = "شكراً لزيارتك {$establishment->name}. ساعدنا بتقييم تجربك لتحسين خدماتنا!";

        $data = [
            'type' => 'completed',
            'booking_id' => $booking->id,
            'establishment_id' => $establishment->id,
            'user_id' => $user->id,
        ];

        if ($user->device_token) {
            $this->firebaseService->sendNotification($user->device_token, $title, $body, $data);
        }

        return ApiResponseClass::sendResponse($booking, 'تم إكمال الحجز بنجاح');

    } catch (Exception $e) {
        return ApiResponseClass::sendError('حدث خطأ أثناء إكمال الحجز: ' . $e->getMessage(), [], 500);
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
            $establishmentOwner = $establishment->owner;
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

        if ($user->id != $establishment->owner_id) {
            return ApiResponseClass::sendError('غير مصرح لك بالتراجع عن حالة هذا الحجز', [], 403);
        }

        $booking = $this->bookingStatusService->revertBookingStatus( $booking, $fields['target_status']);

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
            return ApiResponseClass::sendResponse($booking,'تم جلب البيانات بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في جلب بيانات الحجز: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $fields = $request->validate([
            'booking_date' =>['required','date','after_or_equal:now'],
        ]);

        try {
            $user = auth('sanctum')->user();
            $booking = $this->bookingRepository->getById($id);
            $allowedStatuses = ['pending', 'waiting_payment', 'paid'];
            if (!in_array($booking->status, $allowedStatuses)) {
                return ApiResponseClass::sendError(
                'لا يمكن تعديل موعد الحجز في الحالة الحالية (' . $booking->status_text . ')', 
                [], 
                400
                );
            }
            
            $oldDate = $booking->booking_date;
            $newDate = $fields['booking_date'];
            if ($oldDate == $newDate) {
                return ApiResponseClass::sendResponse($booking, 'لم يتم تغيير تاريخ الحجز (نفس التاريخ)');
            }
            $booking->booking_date = $newDate;
            $booking->save();
            $establishment = $booking->establishment;
            $customer = $booking->user;
            if ($user->id == $establishment->owner_id) {
            $modifierType = 'owner';
            $modifierName = $establishment->name;
            $targetUser = $customer;
            
        } else {
            $modifierType = 'customer';
            $modifierName = $customer->name;
            $targetUser = $establishment->owner;
        }
           $targetUser->notify(new BookingModifiedNotification(
            $booking,
            $oldDate,
            $newDate,
            $modifierType,
            $modifierName
        ));
        $title = "تم تعديل موعد الحجز";
        $body = $this->getFirebaseMessage($modifierType, $establishment->name, $customer->name, $oldDate, $newDate);
        
        $data = [
            'type' => 'booking_modified',
            'booking_id' => $booking->id,
            'establishment_id' => $establishment->id,
            'user_id' => $customer->id,
            'action' => 'view_booking',
        ];
        if ($targetUser->device_token) {
            $this->firebaseService->sendNotification($targetUser->device_token, $title, $body, $data);
        }
            return ApiResponseClass::sendResponse($booking, 'تم تحديث تاريخ الحجز بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ أثناء تحديث تاريخ الحجز: ' . $e->getMessage(), [], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
