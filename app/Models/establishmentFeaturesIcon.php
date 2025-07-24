<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class establishmentFeaturesIcon extends Model
{
    use HasFactory;
    protected $fillable = ['icon'];

    public function getIconAttribute($value)
    { 
        return 'storage/'.$value;
    }
}
