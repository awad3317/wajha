<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pricePackageIcon extends Model
{
    use HasFactory;
    protected $fillable = ['icon'];

    public function getIconAttribute($value)
    {
        if (request()->is('api/*')) {
            return 'storage/'.$value;
        }
        return $value;
    }

    /**
     * Get the price packages for the pricePackageIcon .
     */
    public function pricePackages()
    {
        return $this->hasMany(PricePackage::class);
    }
}
