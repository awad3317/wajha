<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Favorite;
use Illuminate\Support\Facades\DB;

class FavoriteRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index($userId)
    {
        return Favorite::with(['establishment:id,name,region_id,primary_image','establishment.region:id,name,parent_id' ,'establishment.region.parent:id,name' ])
        ->where('user_id','=',$userId)->paginate(10);
    }

    /**
     * Retrieve a Favorite by ID.
     */
    public function getById($id): Favorite
    {
        return Favorite::find($id);
    }

    /**
     * Store a new Favorite.
     */
    public function store(array $data): Favorite
    {
        return Favorite::create($data);
    }

    /**
     * Update an existing Favorite.
     */
    public function update(array $data, $id): Favorite
    {
        $Favorite = Favorite::findOrFail($id);
        $Favorite->update($data);
        return $Favorite;
    }

    /**
     * Delete a Favorite by ID.
     */
    public function delete($id): bool
    {
        return Favorite::where('id', $id)->delete() > 0;
    }

    public function grtByUserIdAndEstablishmentId($user_id,$establishment_id)  {
        return Favorite::where('user_id', $user_id)
        ->where('establishment_id', $establishment_id)
        ->first();
    }
    
}
