<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstablishmentImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'image'
    ];

    // Relationship with establishment
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    //  Accessor for full image URL
    public function getImageAttribute($value)
    {
         if (request()->is('api/*')) {
            return 'storage/'.$value;
        }
        return $value;
    }
}
