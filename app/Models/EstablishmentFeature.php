<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstablishmentFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'name',
        'description',
        'icon_id'
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function icon()
    {
        return $this->belongsTo(establishmentFeaturesIcon::class, 'icon_id');
    }
}
