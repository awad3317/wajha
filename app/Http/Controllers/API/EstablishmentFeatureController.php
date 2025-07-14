<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\EstablishmentRepository;
use App\Repositories\EstablishmentFeatureRepository;

class EstablishmentFeatureController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private EstablishmentFeatureRepository $EstablishmentFeatureRepository,private EstablishmentRepository $EstablishmentRepository)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $Features = $this->EstablishmentFeatureRepository->index();
            return ApiResponseClass::sendResponse($Features, 'Establishment features retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('An error occurred while fetching establishment features.', $e->getMessage());
        }
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'establishment_id' => ['required',Rule::exists('establishments','id')],
            'name' => ['required','string','max:255'],
            'description'=>['nullable','string','max:1000','min:2'],
            'icon' => ['required','string','max:255'],
        ]);
        try {
            $user = auth('sanctum')->user();
            $establishment= $this->EstablishmentRepository->getById($fields['establishment_id']);
            if ($establishment->owner_id != $user->id) {
                return ApiResponseClass::sendError('Only the owner of the establishment can add features. ', null, 403);
            }
            $feature = $establishment->features()->create([
            'name' => $fields['name'],
            'description'=>$fields['description'] ?? null,
            'icon' => $fields['icon']
        ]);
            return ApiResponseClass::sendResponse($feature, 'Establishment feature saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error saving establishment feature: ' . $e->getMessage());
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
            'establishment_id' => ['sometimes',Rule::exists('establishments','id')],
            'name' => ['sometimes','string','max:255'],
            'icon' => ['sometimes','string','max:255'],
        ]);
        try {
            $feature = $this->EstablishmentFeatureRepository->getById($id);
            $establishment = $feature->establishment;
            if ($establishment->owner_id != auth('sanctum')->user()->id) {
                return ApiResponseClass::sendError('Only the owner of the establishment can update features.', null, 403);
            }
            $feature = $this->EstablishmentFeatureRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($feature,'establishment feature update successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error update establishment feature: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $feature = $this->EstablishmentFeatureRepository->getById($id);
            $establishment = $feature->establishment;
            if ($establishment->owner_id != auth('sanctum')->user()->id) {
                return ApiResponseClass::sendError('Only the owner of the establishment can delete features.', null, 403);
            }
           if($this->EstablishmentFeatureRepository->delete($id)) {
                return ApiResponseClass::sendResponse($feature, "{$feature->id} unsaved successfully.");
           }
           return ApiResponseClass::sendError("Acount with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting establishment feature: ' . $e->getMessage());
        }
    }
}
