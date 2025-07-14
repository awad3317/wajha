<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\pricePackageIcon;

class pricePackageIconRepository implements RepositoriesInterface
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
        return pricePackageIcon::get();
    }

    /**
     * Retrieve a pricePackageIcon by ID.
     */
    public function getById($id): pricePackageIcon
    {
        return pricePackageIcon::findOrFail($id);
    }

    /**
     * Store a new pricePackageIcon.
     */
    public function store(array $data): pricePackageIcon
    {
        return pricePackageIcon::create($data);
    }

    /**
     * Update an existing pricePackageIcon.
     */
    public function update(array $data, $id): pricePackageIcon
    {
        $pricePackageIcon = pricePackageIcon::findOrFail($id);
        $pricePackageIcon->update($data);
        return $pricePackageIcon;
    }

    /**
     * Delete a pricePackageIcon by ID.
     */
    public function delete($id): bool
    {
        return pricePackageIcon::where('id', $id)->delete() > 0;
    }
    
}
