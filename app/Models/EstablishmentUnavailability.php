<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstablishmentUnavailability extends Model
{
    use HasFactory;

    protected $fillable = ['establishment_id','price_package_id', 'unavailable_date'];

    protected $casts = [
        'unavailable_date' => 'date'
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function pricePackage()
    {
        return $this->belongsTo(PricePackage::class);
    }
}
