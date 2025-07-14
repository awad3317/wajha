<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking extends Model
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

}
