<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\OwnerAccount;
use Illuminate\Support\Facades\DB;

class OwnerAccountRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all OwnerAccounts with pagination.
     */
    public function index()
    {
        return OwnerAccount::where('owner_id','=',auth( 'sanctum')->id())->get();
    }

    /**
     * Retrieve a OwnerAccount by ID.
     */
    public function getById($id): OwnerAccount
    {
        return OwnerAccount::findOrFail($id);
    }

    /**
     * Store a new OwnerAccount.
     */
    public function store(array $data): OwnerAccount
    {
        return OwnerAccount::create($data);
    }

    /**
     * Update an existing OwnerAccount.
     */
    public function update(array $data, $id): OwnerAccount
    {
        $OwnerAccount = OwnerAccount::findOrFail($id);
        $OwnerAccount->update($data);
        return $OwnerAccount;
    }

    /**
     * Delete a OwnerAccount by ID.
     */
    public function delete($id): bool
    {
        return OwnerAccount::where('id', $id)->delete() > 0;
    }
    
}
