<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\EstablishmentRule;
use Illuminate\Support\Facades\DB;

class EstablishmentRuleRepository implements RepositoriesInterface
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
        return EstablishmentRule::get();
    }

    /**
     * Retrieve a EstablishmentRule by ID.
     */
    public function getById($id): EstablishmentRule
    {
        return EstablishmentRule::findOrFail($id);
    }

    /**
     * Store a new EstablishmentRule.
     */
    public function store(array $data): EstablishmentRule
    {
        return EstablishmentRule::create($data);
    }

    /**
     * Update an existing EstablishmentRule.
     */
    public function update(array $data, $id): EstablishmentRule
    {
        $EstablishmentRule = EstablishmentRule::findOrFail($id);
        $EstablishmentRule->update($data);
        return $EstablishmentRule;
    }

    /**
     * Delete a EstablishmentRule by ID.
     */
    public function delete($id): bool
    {
        return EstablishmentRule::where('id', $id)->delete() > 0;
    }
    
}
