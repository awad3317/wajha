<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\EstablishmentType;
use Illuminate\Support\Facades\DB;

class EstablishmentTypeRepository implements RepositoriesInterface
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
        return EstablishmentType::get();
    }

    /**
     * Retrieve a EstablishmentType by ID.
     */
    public function getById($id): EstablishmentType
    {
        return EstablishmentType::findOrFail($id);
    }

    /**
     * Store a new EstablishmentType.
     */
    public function store(array $data): EstablishmentType
    {
        return EstablishmentType::create($data);
    }

    /**
     * Update an existing EstablishmentType.
     */
    public function update(array $data, $id): EstablishmentType
    {
        $EstablishmentType = EstablishmentType::findOrFail($id);
        $EstablishmentType->update($data);
        return $EstablishmentType;
    }

    /**
     * Delete a EstablishmentType by ID.
     */
    public function delete($id): bool
    {
        return EstablishmentType::where('id', $id)->delete() > 0;
    }
    
}
