<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\booking;

class bookingRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index($user_id)
    {
        return booking::where('user_id', $user_id)->paginate(10);
    }

    /**
     * Retrieve a booking by ID.
     */
    public function getById($id): booking
    {
        return booking::with(['user','establishment.owner','pricePackage.currency','pricePackage.icon','coupon'])->findOrFail($id);
    }

    /**
     * Store a new booking.
     */
    public function store(array $data): booking
    {
        return booking::create($data);
    }

    /**
     * Update an existing booking.
     */
    public function update(array $data, $id): booking
    {
        $booking = booking::findOrFail($id);
        $booking->update($data);
        return $booking;
    }

    /**
     * Delete a booking by ID.
     */
    public function delete($id): bool
    {
        return booking::where('id', $id)->delete() > 0;
    }

    public function findExistingBooking($user_id,$establishment_id){
        return Booking::where('user_id', $user_id)
            ->where('establishment_id', $establishment_id)
            ->whereIn('status', ['pending', 'waiting_payment', 'paid'])
            ->first();
    }
    
}
