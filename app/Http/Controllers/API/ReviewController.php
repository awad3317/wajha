<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Repositories\ReviewRepository;
use App\Classes\ApiResponseClass;

class ReviewController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private ReviewRepository $ReviewRepository)
    {
        //
    }

    public function upsertReview(Request $request)
    {
        $fields=$request->validate([
            'establishment_id' => ['required',Rule::exists('establishments','id')], 
            'rating' =>['required','integer','min:1','max:5'], 
        ]);
        $fields['user_id'] = auth('sanctum')->id();
        $review=$this->ReviewRepository->getByUserIdAndEstablishmentId($fields['user_id'], $fields['establishment_id']);
        if($review){
            $review=$this->ReviewRepository->update($fields, $review->id);
            return ApiResponseClass::sendResponse($review, 'Review updated successfully.');
        }
        $review = $this->ReviewRepository->store($fields);
        return ApiResponseClass::sendResponse($review, 'Review created successfully.');
    }
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
