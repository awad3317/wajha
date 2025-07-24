<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Currency;

class CurrencyRepository implements RepositoriesInterface
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
        return Currency::get();
    }

    /**
     * Retrieve a Currency by ID.
     */
    public function getById($id): Currency
    {
        return Currency::findOrFail($id);
    }

    /**
     * Store a new Currency.
     */
    public function store(array $data): Currency
    {
        return Currency::create($data);
    }

    /**
     * Update an existing Currency.
     */
    public function update(array $data, $id): Currency
    {
        $Currency = Currency::findOrFail($id);
        $Currency->update($data);
        return $Currency;
    }

    /**
     * Delete a Currency by ID.
     */
    public function delete($id): bool
    {
        return Currency::where('id', $id)->delete() > 0;
    }
    
}
