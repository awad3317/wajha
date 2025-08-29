<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\EstablishmentTypeRepository;

class EstablishmentTypeController extends Controller
{
      /**
     * Create a new class instance.
     */
    public function __construct(private EstablishmentTypeRepository $EstablishmentTypeRepository)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $EstablishmentTypes=$this->EstablishmentTypeRepository->index();
            return ApiResponseClass::sendResponse($EstablishmentTypes,'تم جلب جميع أنواع المنشآت بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في جلب أنواع المنشآت: '  . $e->getMessage());
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
