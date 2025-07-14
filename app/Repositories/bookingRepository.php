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

    public function index()
    {
        return booking::paginate(10);
    }

    /**
     * Retrieve a booking by ID.
     */
    public function getById($id): booking
    {
        return booking::findOrFail($id);
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
    
}
