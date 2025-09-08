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
            return ApiResponseClass::sendResponse($Favorite,  'تم جلب المنشآت المفضلة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ أثناء جلب المنشآت المفضلة', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function toggleFavorite(Request $request)
    {
       $fields=$request->validate([
            'establishment_id' => ['required',Rule::exists('establishments','id')],
        ], [
            'establishment_id.required' => 'يجب اختيار منشئة.',
            'establishment_id.exists' => 'المنشئة المحددة غير موجودة.',
        ]);
        try {
            $fields['user_id'] = auth('sanctum')->user()->id; 
            $favorite = $this->FavoriteRepository->grtByUserIdAndEstablishmentId($fields['user_id'],$fields['establishment_id']);
            if ($favorite) {
                $favorite->delete();
                $message = 'تم إزالة المنشأة من المفضلة بنجاح';
            } else {
                $favorite = $this->FavoriteRepository->store($fields);
                $message = 'تم إضافة المنشأة إلى المفضلة بنجاح';
            }
            return ApiResponseClass::sendResponse($favorite, $message);
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في حفظ المفضلة: ' . $e->getMessage());
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
