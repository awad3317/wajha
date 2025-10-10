<?php

namespace App\Repositories;
use App\Models\booking;
use App\Models\Establishment;
use App\Interfaces\RepositoriesInterface;

class bookingRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function indexForOwner($user)
    {
        $establishmentIds = Establishment::where('owner_id', $user->id)->pluck('id');
        // return $establishmentIds;
        $bookings = Booking::with(['establishment'])->whereIn('establishment_id', $establishmentIds)->get();
        return $bookings;
    }

    public function index($user_id)
    {
        return Booking::with(['establishment'])->where('user_id', $user_id)->get();
    }

    /**
     * Retrieve a booking by ID.
     */
    public function getById($id): booking
    {
        return Booking::with(['user','establishment.owner.ownerAccounts.bank','pricePackage.currency','pricePackage.icon','coupon'])->findOrFail($id);
    }

    /**
     * Store a new booking.
     */
    public function store(array $data): booking
    {
        return Booking::create($data);
    }

    /**
     * Update an existing booking.
     */
    public function update(array $data, $id): Booking
    {
        $booking = Booking::findOrFail($id);
        $booking->update($data);
        return $booking;
    }

    /**
     * Delete a booking by ID.
     */
    public function delete($id): bool
    {
        return Booking::where('id', $id)->delete() > 0;
    }

    public function findExistingBooking($user_id,$establishment_id){
        return Booking::where('user_id', $user_id)
            ->where('establishment_id', $establishment_id)
            ->whereIn('status', ['pending', 'waiting_payment', 'paid'])
            ->first();
    }
    
}