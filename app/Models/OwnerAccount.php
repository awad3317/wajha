<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'bank_id',
        'account_number'
    ];

     public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
