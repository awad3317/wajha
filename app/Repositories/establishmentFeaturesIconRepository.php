<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\EstablishmentFeaturesIcon;

class establishmentFeaturesIconRepository implements RepositoriesInterface
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
        return EstablishmentFeaturesIcon::get();
    }

    /**
     * Retrieve a EstablishmentFeaturesIcon by ID.
     */
    public function getById($id): EstablishmentFeaturesIcon
    {
        return EstablishmentFeaturesIcon::findOrFail($id);
    }

    /**
     * Store a new EstablishmentFeaturesIcon.
     */
    public function store(array $data): EstablishmentFeaturesIcon
    {
        return EstablishmentFeaturesIcon::create($data);
    }

    /**
     * Update an existing EstablishmentFeaturesIcon.
     */
    public function update(array $data, $id): EstablishmentFeaturesIcon
    {
        $establishmentFeaturesIcon = EstablishmentFeaturesIcon::findOrFail($id);
        $establishmentFeaturesIcon->update($data);
        return $establishmentFeaturesIcon;
    }

    /**
     * Delete a establishmentFeaturesIcon by ID.
     */
    public function delete($id): bool
    {
        return EstablishmentFeaturesIcon::where('id', $id)->delete() > 0;
    }
    
}