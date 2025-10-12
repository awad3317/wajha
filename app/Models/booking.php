<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'establishment_id',
        'price_package_id',
        'booking_date',
        'payment_receipt_image',
        'coupon_id',
        'discount_amount',
        'status'
    ];

    
    protected $casts = [
        'booking_date' => 'datetime',
        'discount_amount' => 'decimal:2'
    ];

    // protected $appends = ['pricing_details'];


    public function getStatusTextAttribute()
    {
        $statusMap = [
            'pending' => 'قيد الانتظار',
            'waiting_payment' => 'بانتظار الدفع',
            'paid' => 'مدفوع',
            'confirmed' => 'تم التأكيد',
            'cancelled' => 'ملغى', 
            'completed' => 'مكتمل'
        ];

        return $statusMap[$this->status] ?? 'غير معروف';
    }

    public function calculatePricing()
    {
        $originalPrice = $this->pricePackage->price ?? 0;

        $discountType = $this->coupon->discount_type ?? null;
        $discountValue = $this->coupon->discount_value ?? 0;

        $finalPrice = $originalPrice;
        $discountAmount = 0;
        $discountLabel = 'بدون خصم';

        if ($discountType === 'percentage' && $discountValue > 0) {
            $discountAmount = $originalPrice * ($discountValue / 100);
            $finalPrice = $originalPrice - $discountAmount;
            $discountLabel = $discountValue . ' %';
        } elseif ($discountType === 'fixed_amount' && $discountValue > 0) {
            $discountAmount = $discountValue;
            $finalPrice = $originalPrice - $discountAmount;
            $discountLabel = number_format($discountValue, 2) . ' ' . ($this->pricePackage->currency->symbol ?? '');
        }

        return [
            'final_price' => max(0, $finalPrice),
            'discount_label' => $discountLabel,
            'original_price' => (float) $originalPrice,
            'discount_type' => $discountType,
            'discount_amount' => (float) $discountAmount,
            'currency' => $this->pricePackage->currency ?? null
        ];
    }

    public function getPaymentReceiptImageAttribute($value)
    {
        return 'storage/' . $value;
    }

    public function getPricingDetailsAttribute()
    {
        return $this->calculatePricing();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function pricePackage()
    {
        return $this->belongsTo(PricePackage::class);
    }

    public function coupon()
    {
        return $this->belongsTo(DiscountCoupon::class);
    }

    public function couponUse()
    {
        return $this->hasOne(CouponUse::class);
    }

}