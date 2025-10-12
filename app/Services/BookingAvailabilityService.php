<?php

namespace App\Services;

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
        return EstablishmentUnavailability::where([
            'price_package_id' => $pricePackageId
        ])->pluck('unavailable_date');
    }
}
