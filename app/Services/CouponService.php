<?php

namespace App\Services;

use App\Models\DiscountCoupon;
use App\Models\Establishment;
use App\Models\CouponUse;
use Carbon\Carbon;

class CouponService
{

    public function getCouponByCode(string $code): ?DiscountCoupon
    {
        return DiscountCoupon::where('code', $code)->first();
    }
    public function isCouponActive(DiscountCoupon $coupon): bool
    {
        return $coupon->is_active;
    }

    public function isWithinValidDates(DiscountCoupon $coupon): bool
    {
        $now = Carbon::now();
        return $now->gte($coupon->start_date) && $now->lte($coupon->end_date);
    }

    public function hasUsageLimitAvailable(DiscountCoupon $coupon): bool
    {
        return $coupon->current_uses < $coupon->max_uses;
    }
   public function isApplicableToEstablishment(DiscountCoupon $coupon, Establishment $establishment): bool
    {
        return match ($coupon->applies_to) {
            'specific_establishments' => $coupon->establishments()->where('establishment_id', $establishment->id)->exists(),
            'specific_types' => $coupon->establishmentTypes()->where('type_id', $establishment->type_id)->exists(),
            'all_establishments' => true,
            default => false,
    };
    }

    public function calculateDiscountAmount(DiscountCoupon $coupon, float $originalPrice): float
    {
        return match ($coupon->discount_type) {
            'percentage' => $originalPrice * ($coupon->discount_value / 100),
            'fixed' => min($coupon->discount_value, $originalPrice),
            default => 0,
        };
    }

    public function recordCouponUse(array $couponData, int $bookingId): void
    {
        CouponUse::create([
            'coupon_id' => $couponData['coupon_id'],
            'user_id' => $couponData['user_id'],
            'booking_id' => $bookingId,
            'discount_amount' => $couponData['discount_amount'],
            'used_at' => now(),
        ]);

        DiscountCoupon::where('id', $couponData['coupon_id'])->increment('current_uses');
    }
}