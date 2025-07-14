<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\EstablishmentFeature;
use Illuminate\Support\Facades\DB;

class EstablishmentFeatureRepository implements RepositoriesInterface
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
        return EstablishmentFeature::with(['region.parent'])->paginate(10);
    }

    /**
     * Retrieve a EstablishmentFeature by ID.
     */
    public function getById($id): EstablishmentFeature
    {
        return EstablishmentFeature::findOrFail($id);
    }

    /**
     * Store a new EstablishmentFeature.
     */
    public function store(array $data): EstablishmentFeature
    {
        if (isset($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }
        return EstablishmentFeature::create($data);
    }

    /**
     * Update an existing EstablishmentFeature.
     */
    public function update(array $data, $id): EstablishmentFeature
    {
        if (isset($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }
        $EstablishmentFeature = EstablishmentFeature::findOrFail($id);
        $EstablishmentFeature->update($data);
        return $EstablishmentFeature;
    }

    /**
     * Delete a EstablishmentFeature by ID.
     */
    public function delete($id): bool
    {
        return EstablishmentFeature::where('id', $id)->delete() > 0;
    }
    
}
