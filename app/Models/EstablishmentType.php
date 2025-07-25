<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstablishmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
    ];

    public function establishments()
    {
        return $this->hasMany(Establishment::class);
    }

     public function coupons()
    {
        return $this->belongsToMany(DiscountCoupon::class, 'coupon_establishment_types', 'type_id', 'coupon_id');
    }

    public function getIconAttribute($value)
    {
        if (request()->is('api/*')) {
            return 'storage/'.$value;
        }
        return $value;
    }
}
