<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstablishmentUnavailability extends Model
{
    use HasFactory;

    protected $fillable = ['establishment_id', 'unavailable_date'];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }
}
