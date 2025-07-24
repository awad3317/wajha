<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\establishmentFeaturesIcon;

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
        return establishmentFeaturesIcon::get();
    }

    /**
     * Retrieve a establishmentFeaturesIcon by ID.
     */
    public function getById($id): establishmentFeaturesIcon
    {
        return establishmentFeaturesIcon::findOrFail($id);
    }

    /**
     * Store a new establishmentFeaturesIcon.
     */
    public function store(array $data): establishmentFeaturesIcon
    {
        return establishmentFeaturesIcon::create($data);
    }

    /**
     * Update an existing establishmentFeaturesIcon.
     */
    public function update(array $data, $id): establishmentFeaturesIcon
    {
        $establishmentFeaturesIcon = establishmentFeaturesIcon::findOrFail($id);
        $establishmentFeaturesIcon->update($data);
        return $establishmentFeaturesIcon;
    }

    /**
     * Delete a establishmentFeaturesIcon by ID.
     */
    public function delete($id): bool
    {
        return establishmentFeaturesIcon::where('id', $id)->delete() > 0;
    }
    
}
