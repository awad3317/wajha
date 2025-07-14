<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Region;
use Illuminate\Support\Facades\DB;

class RegionRepository implements RepositoriesInterface
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
        return Region::select('id', 'name')->paginate(10);
    }

    /**
     * Retrieve a Region by ID.
     */
    public function getById($id): Region
    {
        return Region::findOrFail($id);
    }

    /**
     * Store a new Region.
     */
    public function store(array $data): Region
    {
        return Region::create($data);
    }

    /**
     * Update an existing Region.
     */
    public function update(array $data, $id): Region
    {
        $Region = Region::findOrFail($id);
        $Region->update($data);
        return $Region;
    }

    /**
     * Delete a Region by ID.
     */
    public function delete($id): bool
    {
        return Region::where('id', $id)->delete() > 0;
    }

    public function getParents(){
        return Region::select('id', 'name')->whereNull('parent_id')->get();
    }

    public function getChildren($id)
    {
        $Region = Region::where('id', $id)->first();
        if (!$Region) {
            return collect(); 
        }
        return Region::select('id', 'name')->where('parent_id', $Region->id)->get();
    }
    
}
