<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\PricePackage;
use Illuminate\Support\Facades\DB;

class PricePackageRepository implements RepositoriesInterface
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
        return PricePackage::get();
    }

    /**
     * Retrieve a PricePackage by ID.
     */
    public function getById($id): PricePackage
    {
        return PricePackage::findOrFail($id);
    }

    /**
     * Store a new PricePackage.
     */
    public function store(array $data): PricePackage
    {
        return PricePackage::create($data);
    }

    /**
     * Update an existing PricePackage.
     */
    public function update(array $data, $id): PricePackage
    {
        $PricePackage = PricePackage::findOrFail($id);
        $PricePackage->update($data);
        return $PricePackage;
    }

    /**
     * Delete a PricePackage by ID.
     */
    public function delete($id): bool
    {
        return PricePackage::where('id', $id)->delete() > 0;
    }
    
}
