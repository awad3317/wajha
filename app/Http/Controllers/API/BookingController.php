<?php

namespace App\Http\Controllers\API;


use Exception;
use Illuminate\Http\Request;
use App\Services\CouponService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Services\FirebaseService;
use App\Services\BookingStatusService;
use App\Http\Controllers\Controller;
use App\Repositories\bookingRepository;
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
