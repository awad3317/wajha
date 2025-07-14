<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\PricePackageRepository;
use App\Repositories\EstablishmentRepository;

class PricePackageController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private PricePackageRepository $PricePackageRepository,private EstablishmentRepository $EstablishmentRepository)
    {
        //
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
        $fields=$request->validate([
            'establishment_id' => ['required',Rule::exists('establishments','id')],
            'icon_id' => ['nullable',Rule::exists('price_package_icons','id')],
            'name' => ['required','string','max:100'],
            'description' => ['nullable','string','max:1000'],
            'price' =>['required','numeric','min:0'],
            'features' => ['nullable', 'array'],
            'features.*' => ['required', 'string', 'max:100'],
        ]);

        try {
            $userId= auth('sanctum')->id();
            $establishment=$this->EstablishmentRepository->getById($fields['establishment_id']);
            if (!$establishment || $establishment->owner_id !== $userId) {
                return ApiResponseClass::sendError('Unauthorized. You are not authorized to add Price Package to this establishment.', [], 403);
            }
            $package=$this->PricePackageRepository->store($fields);
            return ApiResponseClass::sendResponse($package, 'Price package created successfully with features stored as JSON.');

        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error saving package: ' . $e->getMessage());
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
        $fields = $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon_id' => ['sometimes',Rule::exists('price_package_icons','id')],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'features' => ['nullable', 'array'],
            'features.*' => ['required_with:features', 'string', 'max:100'],
        ]);
        try {
            $userId = auth('sanctum')->id();
            $package = $this->PricePackageRepository->getById($id);
            $establishment = $package->establishment;
            if (!$establishment || $establishment->owner_id !== $userId) {
            return ApiResponseClass::sendError('Unauthorized. You are not authorized to update this price package.', [], 403);
        }
            $updatedPackage = $this->PricePackageRepository->update($fields, $id);
            return ApiResponseClass::sendResponse($updatedPackage, 'Price package updated successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error updating package: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    { 
        try {
            $userId = auth('sanctum')->id();
            $package=$this->PricePackageRepository->getById($id);
            $establishment = $package->establishment;
            if (!$establishment || $establishment->owner_id !== $userId) {
                return ApiResponseClass::sendError('Unauthorized. You are not authorized to delete this price package.', [], 403);
            }
            if($this->PricePackageRepository->delete($id)){
                return ApiResponseClass::sendResponse($package, "{$package->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("Package with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting package: ' . $e->getMessage());
        }
    }
}
