<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstablishmentFeaturesIcon extends Model
{
    use HasFactory;
    protected $fillable = ['icon'];

    public function getIconAttribute($value)
    { 
        return 'storage/'.$value;
    }

    public function features()
    {
        return $this->hasMany(EstablishmentFeature::class, 'icon_id');
    }
}