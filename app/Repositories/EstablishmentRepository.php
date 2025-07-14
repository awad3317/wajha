<?php

namespace App\Repositories;
use App\Models\Region;
use App\Models\Establishment;
use Illuminate\Support\Facades\DB;
use App\Interfaces\RepositoriesInterface;

class EstablishmentRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index($request)
    {
        $query = Establishment::select(['id', 'name', 'primary_image', 'region_id','type_id'])
            ->with([
                'region' => function ($q) {
                    $q->select('id', 'name', 'parent_id');
                },
                'region.parent' => function ($q) {
                    $q->select('id', 'name');
                }
            ])
            ->withAvg('reviews', 'rating')->where('is_verified','=',true);

        $region_id = $request->query('region_id');
        if ($region_id) {
            $region = Region::with('children')->where('id', $region_id)->first();
            if ($region) {
                $regionIds = $region->children()->pluck('id')->push($region->id);
                $allRegionIds = Region::whereIn('parent_id', $regionIds)->pluck('id');
                $regionIds = $regionIds->merge($allRegionIds);
                $query->whereIn('region_id', $regionIds);
            }
        }
        return $query->filter()->paginate(10);
    }
    

    /**
     * Retrieve a Establishment by ID.
     */
    public function getById($id): Establishment
    {
        return Establishment::with(['region.parent','images','features','rules','pricePackages','specifications','unavailabilityDays'])->withAvg('reviews', 'rating')->where('is_verified','=',true)->findOrFail($id);
    }

    /**
     * Store a new Establishment.
     */
    public function store(array $data): Establishment
    {
        return Establishment::create($data);
    }

    /**
     * Update an existing Establishment.
     */
    public function update(array $data, $id): Establishment
    {
        $Establishment = Establishment::findOrFail($id);
        $Establishment->update($data);
        return $Establishment;
    }

    /**
     * Delete a Establishment by ID.
     */
    public function delete($id): bool
    {
        return Establishment::where('id', $id)->delete() > 0;
    }
    
}
