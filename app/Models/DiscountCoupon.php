<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'max_uses',
        'is_active',
        'applies_to',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

   /**
     * Get the establishments that the coupon applies to.
     */
    public function establishments()
    {
        return $this->belongsToMany(Establishment::class, 'coupon_establishments', 'coupon_id', 'establishment_id');
    }

     /**
     * Get the establishment types that the coupon applies to.
     */
    public function establishmentTypes()
    {
        return $this->belongsToMany(EstablishmentType::class, 'coupon_establishment_types', 'coupon_id', 'type_id');
    }

    /**
     * Get the coupon uses for the coupon.
     */
    public function uses()
    {
        return $this->hasMany(CouponUse::class, 'coupon_id');
    }

    public function bookings()
    {
        return $this->hasMany(booking::class);
    }
}
