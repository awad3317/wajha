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
        ], [
            'establishment_id.required' => 'يجب اختيار منشأة.',
            'establishment_id.exists' => 'المنشأة غير موجودة.',
            'rating.required' => 'يجب إدخال التقييم.',
            'rating.integer' => 'يجب أن يكون التقييم رقمًا صحيحًا.',
            'rating.min' => 'يجب أن يكون التقييم على الأقل 1.',
            'rating.max' => 'يجب أن لا يتجاوز التقييم 5.',
        ]);
        $fields['user_id'] = auth('sanctum')->id();
        $review=$this->ReviewRepository->getByUserIdAndEstablishmentId($fields['user_id'], $fields['establishment_id']);
        if($review){
            $review=$this->ReviewRepository->update($fields, $review->id);
            return ApiResponseClass::sendResponse($review, 'تم تحديث التقييم بنجاح.');
        }
        $review = $this->ReviewRepository->store($fields);
        return ApiResponseClass::sendResponse($review, 'تم إنشاء التقييم بنجاح.');
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
