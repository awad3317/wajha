<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponEstablishment extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['coupon_id','establishment_id'];
}
