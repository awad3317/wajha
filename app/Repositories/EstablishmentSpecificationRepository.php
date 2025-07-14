<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\EstablishmentSpecification;

class EstablishmentSpecificationRepository implements RepositoriesInterface
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
        return EstablishmentSpecification::paginate(10);
    }

    /**
     * Retrieve a EstablishmentSpecification by ID.
     */
    public function getById($id): EstablishmentSpecification
    {
        return EstablishmentSpecification::findOrFail($id);
    }

    /**
     * Store a new EstablishmentSpecification.
     */
    public function store(array $data): EstablishmentSpecification
    {
        return EstablishmentSpecification::create($data);
    }

    /**
     * Update an existing EstablishmentSpecification.
     */
    public function update(array $data, $id): EstablishmentSpecification
    {
        $EstablishmentSpecification = EstablishmentSpecification::findOrFail($id);
        $EstablishmentSpecification->update($data);
        return $EstablishmentSpecification;
    }

    /**
     * Delete a EstablishmentSpecification by ID.
     */
    public function delete($id): bool
    {
        return EstablishmentSpecification::where('id', $id)->delete() > 0;
    }
    
}
