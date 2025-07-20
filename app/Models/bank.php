<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bank extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'icon',
    ];

    public function getIconAttribute($value)
    {
        return 'storage/'.$value;
    }

    /**
     * Get the owner accounts for this bank.
     */
    public function ownerAccounts()
    {
        return $this->hasMany(OwnerAccount::class);
    }
}
