<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUse extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'user_id',
        'booking_id',
        'discount_amount'
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'discount_amount' => 'decimal:2'
    ];
    
    public function coupon()
    {
        return $this->belongsTo(DiscountCoupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
