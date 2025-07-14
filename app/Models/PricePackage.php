<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PricePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'name',
        'description',
        'icon_id',
        'price',
        'features',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function icon()
    {
        return $this->belongsTo(pricePackageIcon::class);
    }

    /**
     * Get the establishment that owns the price package.
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function bookings()
    {
        return $this->hasMany(booking::class);
    }
}
