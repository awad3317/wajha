<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\pricePackageIconRepository;

class pricePackageIconController extends Controller
{
      /**
     * Create a new class instance.
     */
    public function __construct(private pricePackageIconRepository $pricePackageIconRepository)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $Icons = $this->pricePackageIconRepository->index();
            return ApiResponseClass::sendResponse($Icons, 'Icons retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('An error occurred while fetching Icons.', $e->getMessage());
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
