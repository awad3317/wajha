<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\ApiResponseClass;
use App\Repositories\establishmentFeaturesIconRepository;

class EstablishmentFeaturesIconController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private establishmentFeaturesIconRepository $establishmentFeaturesIconRepository)
    {
        //
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $FeaturesIcon=$this->establishmentFeaturesIconRepository->index();
            return ApiResponseClass::sendResponse($FeaturesIcon,'تم جلب جميع أيقونات الميزات بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في جلب أيقونات الميزات: ' . $e->getMessage());
        }
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
