<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'phone_verified_at',
        'is_banned',
        'password',
        'user_type',  
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function establishments()
    {
        return $this->hasMany(Establishment::class, 'owner_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get the reviews for the user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
    * Get the user's bookings.
    */
    public function bookings()
    {
        return $this->hasMany(booking::class);
    }

    public function createdCoupons()
    {
        return $this->hasMany(DiscountCoupon::class, 'created_by');
    }

    public function couponUses()
    {
        return $this->hasMany(CouponUse::class);
    }

    public function usedCoupons()
    {
        return $this->belongsToMany(DiscountCoupon::class, 'coupon_uses')
                ->withPivot('discount_amount', 'used_at', 'booking_id')
                ->using(CouponUse::class);
    }
}
