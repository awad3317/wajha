<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Bank;

class bankRepository implements RepositoriesInterface
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
        return Bank::get();
    }

    /**
     * Retrieve a Bank by ID.
     */
    public function getById($id): Bank
    {
        return Bank::findOrFail($id);
    }

    /**
     * Store a new Bank.
     */
    public function store(array $data): Bank
    {
        return Bank::create($data);
    }

    /**
     * Update an existing Bank.
     */
    public function update(array $data, $id): Bank
    {
        $bank = Bank::findOrFail($id);
        $bank->update($data);
        return $bank;
    }

    /**
     * Delete a bank by ID.
     */
    public function delete($id): bool
    {
        return Bank::where('id', $id)->delete() > 0;
    }
    
}