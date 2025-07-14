<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'establishment_id',
        'added_at'
    ];

    protected $casts = [
        'added_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }
}
