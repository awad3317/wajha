<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\RegionRepository;

class RegionController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private RegionRepository $RegionRepository)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $Regions=$this->RegionRepository->index();
            return ApiResponseClass::sendResponse($Regions,'All Regions retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Regions: ' . $e->getMessage());
        }
    }

    public function getParents()
    {
        try {
            $Parents=$this->RegionRepository->getParents();
            return ApiResponseClass::sendResponse($Parents,'All Parents retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Parents: ' . $e->getMessage());
        }

    }

    public function getChildren($id)
    {
        try {
            $Children=$this->RegionRepository->getChildren($id);
            return ApiResponseClass::sendResponse($Children,'All Children retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Children: ' . $e->getMessage());
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
