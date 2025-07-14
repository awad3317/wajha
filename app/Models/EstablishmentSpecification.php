<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstablishmentSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'establishment_id',
        'icon',
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    
}
