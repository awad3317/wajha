<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\PricePackageIcon;

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
        return PricePackageIcon::get();
    }

    /**
     * Retrieve a PricePackageIcon by ID.
     */
    public function getById($id): PricePackageIcon
    {
        return PricePackageIcon::findOrFail($id);
    }

    /**
     * Store a new PricePackageIcon.
     */
    public function store(array $data): PricePackageIcon
    {
        return PricePackageIcon::create($data);
    }

    /**
     * Update an existing PricePackageIcon.
     */
    public function update(array $data, $id): PricePackageIcon
    {
        $pricePackageIcon = PricePackageIcon::findOrFail($id);
        $pricePackageIcon->update($data);
        return $pricePackageIcon;
    }

    /**
     * Delete a pricePackageIcon by ID.
     */
    public function delete($id): bool
    {
        return PricePackageIcon::where('id', $id)->delete() > 0;
    }
    
}