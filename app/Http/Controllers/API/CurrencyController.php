<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\CurrencyRepository;

class CurrencyController extends Controller
{

    
     /**
     * Create a new class instance.
     */
    public function __construct(private CurrencyRepository $CurrencyRepository)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $Currencies=$this->CurrencyRepository->index();
            return ApiResponseClass::sendResponse($Currencies,'تم جلب جميع العملات بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في جلب العملات: ' . $e->getMessage());
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
