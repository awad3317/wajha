<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Establishment extends Model
{
    use HasFactory, FilterQueryString;

    protected $filters = ['type_id','like'];

    protected $fillable = [
        'owner_id',
        'type_id',
        'region_id',
        'name',
        'description',
        'primary_image',
        'address',
        'latitude',
        'longitude',
        'is_verified',
        'is_active'
    ];

    public function getPrimaryImageAttribute($value)
    {
        if (request()->is('api/*')) {
            return 'storage/'.$value;
        }
        return $value;
    }

    // Relationship with owner (User)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relationship with establishment type
    public function type()
    {
        return $this->belongsTo(EstablishmentType::class, 'type_id');
    }

    // Relationship with region
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    // Relationship with images
    public function images()
    {
        return $this->hasMany(EstablishmentImage::class);
    }

    public function features()
    {
        return $this->hasMany(EstablishmentFeature::class);
    }

    public function rules()
    {
        return $this->hasMany(EstablishmentRule::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    /**
     * Get the price packages for the establishment.
     */
    public function pricePackages()
    {
        return $this->hasMany(PricePackage::class);
    }

    /**
     * Get the reviews for the establishment.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function specifications()
    {
        return $this->hasMany(EstablishmentSpecification::class);
    }

    public function unavailabilityDays()
    {
        return $this->hasMany(EstablishmentUnavailability::class);
    }

    /**
    * Get the bookings for the establishment.
    */
    public function bookings()
    {
        return $this->hasMany(booking::class);
    }

    /**
    * Get the active bookings for the establishment.
    */
    public function activeBookings()
    {
        return $this->hasMany(booking::class)
                ->whereNotIn('status', ['cancelled', 'completed']);
    }
}
