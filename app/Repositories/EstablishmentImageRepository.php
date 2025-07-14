<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\EstablishmentImage;
use Illuminate\Support\Facades\DB;

class EstablishmentImageRepository implements RepositoriesInterface
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
        return EstablishmentImage::get();
    }

    /**
     * Retrieve a EstablishmentImage by ID.
     */
    public function getById($id): EstablishmentImage
    {
        return EstablishmentImage::findOrFail($id);
    }

    /**
     * Store a new EstablishmentImage.
     */
    public function store(array $data): EstablishmentImage
    {
        return EstablishmentImage::create($data);
    }

    /**
     * Update an existing EstablishmentImage.
     */
    public function update(array $data, $id): EstablishmentImage
    {
        $EstablishmentImage = EstablishmentImage::findOrFail($id);
        $EstablishmentImage->update($data);
        return $EstablishmentImage;
    }

    /**
     * Delete a EstablishmentImage by ID.
     */
    public function delete($id): bool
    {
        return EstablishmentImage::where('id', $id)->delete() > 0;
    }
    
}
