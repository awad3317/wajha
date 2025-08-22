<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_id',
        'user_id',
        'from_status',
        'to_status',
        'action',
        'notes',
        'ip_address',
    ];
     protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

     public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
