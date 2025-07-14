<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;

class AdvertisementRepository implements RepositoriesInterface
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
        return Advertisement::select(['id','title','description','image'])->where('is_active','=',true)->get();
    }

    /**
     * Retrieve a Advertisement by ID.
     */
    public function getById($id): Advertisement
    {
        return Advertisement::findOrFail($id);
    }

    /**
     * Store a new Advertisement.
     */
    public function store(array $data): Advertisement
    {
        return Advertisement::create($data);
    }

    /**
     * Update an existing Advertisement.
     */
    public function update(array $data, $id): Advertisement
    {
        $Advertisement = Advertisement::findOrFail($id);
        $Advertisement->update($data);
        return $Advertisement;
    }

    /**
     * Delete a Advertisement by ID.
     */
    public function delete($id): bool
    {
        return Advertisement::where('id', $id)->delete() > 0;
    }
    
}
