<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\EstablishmentUnavailability;

class EstablishmentUnavailabilityRepository implements RepositoriesInterface
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
        return EstablishmentUnavailability::paginate(10);
    }

    /**
     * Retrieve a EstablishmentUnavailability by ID.
     */
    public function getById($id): EstablishmentUnavailability
    {
        return EstablishmentUnavailability::findOrFail($id);
    }

    /**
     * Store a new EstablishmentUnavailability.
     */
    public function store(array $data): EstablishmentUnavailability
    {
        return EstablishmentUnavailability::create($data);
    }

    /**
     * Update an existing EstablishmentUnavailability.
     */
    public function update(array $data, $id): EstablishmentUnavailability
    {
        $EstablishmentUnavailability = EstablishmentUnavailability::findOrFail($id);
        $EstablishmentUnavailability->update($data);
        return $EstablishmentUnavailability;
    }

    /**
     * Delete a EstablishmentUnavailability by ID.
     */
    public function delete($id): bool
    {
        return EstablishmentUnavailability::where('id', $id)->delete() > 0;
    }
    
}
