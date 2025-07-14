<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\bank;
use Illuminate\Support\Facades\DB;

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
        return bank::get();
    }

    /**
     * Retrieve a bank by ID.
     */
    public function getById($id): bank
    {
        return bank::findOrFail($id);
    }

    /**
     * Store a new bank.
     */
    public function store(array $data): bank
    {
        return bank::create($data);
    }

    /**
     * Update an existing bank.
     */
    public function update(array $data, $id): bank
    {
        $bank = bank::findOrFail($id);
        $bank->update($data);
        return $bank;
    }

    /**
     * Delete a bank by ID.
     */
    public function delete($id): bool
    {
        return bank::where('id', $id)->delete() > 0;
    }
    
}
