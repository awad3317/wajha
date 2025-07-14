<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstablishmentRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'rule'
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }
}
