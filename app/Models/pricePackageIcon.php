<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PricePackageIcon extends Model
{
    use HasFactory;
    protected $fillable = ['icon'];

    public function getIconAttribute($value)
    { 
        return 'storage/'.$value;
    }

    /**
     * Get the price packages for the pricePackageIcon .
     */
    public function pricePackages()
    {
        return $this->hasMany(PricePackage::class);
    }
}