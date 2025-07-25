<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'symbol','icon'];

    public function getIconAttribute($value)
    {
        return 'storage/'.$value;
    }
}
