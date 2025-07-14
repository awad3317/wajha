<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewRepository implements RepositoriesInterface
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
        return Review::get();
    }

    /**
     * Retrieve a Review by ID.
     */
    public function getById($id): Review
    {
        return Review::findOrFail($id);
    }

    /**
     * Store a new Review.
     */
    public function store(array $data): Review
    {
        return Review::create($data);
    }

    /**
     * Update an existing Review.
     */
    public function update(array $data, $id): Review
    {
        $Review = Review::findOrFail($id);
        $Review->update($data);
        return $Review;
    }

    /**
     * Delete a Review by ID.
     */
    public function delete($id): bool
    {
        return Review::where('id', $id)->delete() > 0;
    }

    public function getByUserIdAndEstablishmentId($user_id,$establishment_id)
    {
        return Review::where('user_id', $user_id)
                        ->where('establishment_id', $establishment_id)
                        ->first();
    }
    
}
