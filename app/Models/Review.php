<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'establishment_id',
        'rating',
    ];

    /**
     * Get the user that owns the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the establishment that the review belongs to.
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }
}
