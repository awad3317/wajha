<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertisement extends Model
{
    
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Advertisements';

    protected $fillable = [
        'title',
        'description',
        'image',
        'is_active',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getImageAttribute($value)
    {
        if (request()->is('api/*')) {
            return 'storage/'.$value;
        }
        return $value;
    }
}
