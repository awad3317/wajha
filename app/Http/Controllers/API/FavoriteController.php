<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\FavoriteRepository;

class FavoriteController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private FavoriteRepository $FavoriteRepository)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $userId=auth('sanctum')->id();
            $Favorite = $this->FavoriteRepository->index($userId);
            return ApiResponseClass::sendResponse($Favorite, 'Establishment Favorite retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('An error occurred while fetching Favorite.', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function toggleFavorite(Request $request)
    {
       $fields=$request->validate([
            'establishment_id' => ['required',Rule::exists('establishments','id')],
        ]);
        try {
            $fields['user_id'] = auth('sanctum')->user()->id; 
            $favorite = $this->FavoriteRepository->grtByUserIdAndEstablishmentId($fields['user_id'],$fields['establishment_id']);
            if ($favorite) {
                $favorite->delete();
                $message = 'Establishment favorite removed successfully.';
            } else {
                $favorite = $this->FavoriteRepository->store($fields);
                $message = 'Establishment favorite saved successfully.';
            }
            return ApiResponseClass::sendResponse($favorite, $message);
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error saving favorite: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
