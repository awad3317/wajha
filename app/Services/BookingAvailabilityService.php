<?php

namespace App\Services;

use App\Models\PricePackage;
use App\Models\EstablishmentUnavailability;

class BookingAvailabilityService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function checkAvailability($establishmentId, $pricePackageId, $date)
    {
        return !EstablishmentUnavailability::where([
            'establishment_id' => $establishmentId,
            'price_package_id' => $pricePackageId,
            'unavailable_date' => $date
        ])->exists();
    }

    public function markAsUnavailable($establishmentId, $pricePackageId, $date)
    {
        return EstablishmentUnavailability::create([
            'establishment_id' => $establishmentId,
            'price_package_id' => $pricePackageId,
            'unavailable_date' => $date
        ]);
    }

    public function markAsAvailable($establishmentId, $pricePackageId, $date)
    {
        return EstablishmentUnavailability::where([
            'establishment_id' => $establishmentId,
            'price_package_id' => $pricePackageId,
            'unavailable_date' => $date
        ])->delete();
    }

    public function getUnavailableDates($pricePackageId)
    {
        $pricePackage = PricePackage::with('establishment')->findOrFail($pricePackageId);
    
        $anyPackageIds = PricePackage::where('establishment_id', $pricePackage->establishment_id)
            ->where('time_period', 'any')
            ->pluck('id');
    
        if ($pricePackage->time_period !== 'any') {
            $anyPackageIds->push($pricePackageId);
        }
    
        return EstablishmentUnavailability::whereIn('price_package_id', $anyPackageIds)
            ->pluck('unavailable_date')
            ->unique()
            ->values();
        }
}
