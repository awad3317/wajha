<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CouponService;
use App\Classes\ApiResponseClass;


class DiscountCouponController extends Controller
{
      /**
     * Create a new class instance.
     */
    public function __construct(private CouponService $coupon_service)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if($request->filled('coupon_code')){
                $coupon=$this->coupon_service->getCouponByCode($request->coupon_code);
                if(!$coupon){
                    return ApiResponseClass::sendError('كود الخصم الذي أدخلته غير موجود.');
                }
                if(!$this->coupon_service->isWithinValidDates($coupon)){
                    return ApiResponseClass::sendError('هذا الكوبون منتهي الصلاحية أو لم يبدأ بعد.');
                }
                if(!$this->coupon_service->hasUsageLimitAvailable($coupon)){
                    return ApiResponseClass::sendError('لقد وصل هذا الكوبون للحد الأقصى من الاستخدام.');
                }
                if (!$this->coupon_service->isCouponActive($coupon)) {
                    return ApiResponseClass::sendError('هذا الكوبون غير نشط حالياً.');
                }
                return ApiResponseClass::sendResponse($coupon, 'تم ارجاع كوبون الخصم ');
            }else{
                return ApiResponseClass::sendError('الرجاء إرسال كود الخصم.');
            }
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطا اثناء التحقق من الكوبون: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
