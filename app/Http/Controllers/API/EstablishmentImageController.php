<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\EstablishmentImageRepository;
use Exception;
use App\Services\ImageService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Repositories\EstablishmentRepository;

class EstablishmentImageController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private EstablishmentImageRepository $EstablishmentImageRepository,private ImageService $imageService, private EstablishmentRepository $establishmentRepository)
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
        try {
            $fields = $request->validate([
                'establishment_id' =>['required',Rule::exists('establishments','id')],
                'image' =>['required','image','max:2048'],
            ]);
            $establishment = $this->establishmentRepository->getById($fields['establishment_id']);
        
            if (auth('sanctum')->id() != $establishment->owner_id) {
                return ApiResponseClass::sendError('Unauthorized: You are not the owner of this establishment.', [], 403);
            }
            $imagePath = $this->imageService->saveImage($fields['image'], 'establishment-image');
            $EstablishmentImage=$this->EstablishmentImageRepository->store([
                'establishment_id' => $fields['establishment_id'],
                'image' => $imagePath,
            ]);

            return ApiResponseClass::sendResponse($EstablishmentImage,'Image saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Failed to saved image: ' . $e->getMessage());
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
        try {
            $fields = $request->validate([
                'image' => ['required', 'image', 'max:2048'],
            ]);

            $establishmentImage = $this->EstablishmentImageRepository->getById($id);
            if (!$establishmentImage) {
                return ApiResponseClass::sendError('Establishment image not found', [], 404);
            }

            $establishment = $this->establishmentRepository->getById($establishmentImage->establishment_id);
            if (auth('sanctum')->id() != $establishment->owner_id) {
                return ApiResponseClass::sendError('Unauthorized: You are not the owner of this establishment.', [], 403);
            }
            $this->imageService->deleteImage($establishmentImage->image);
            $imagePath = $this->imageService->saveImage($fields['image'], 'establishment-image');
            $updated = $this->EstablishmentImageRepository->update([
                'image' => $imagePath,
            ], $id);
            return ApiResponseClass::sendResponse($updated, 'Image updated successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Failed to update image: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $establishmentImage = $this->EstablishmentImageRepository->getById($id);
            $establishment = $this->establishmentRepository->getById($establishmentImage->establishment_id);
            if (auth('sanctum')->id() != $establishment->owner_id) {
                return ApiResponseClass::sendError('Unauthorized: You are not the owner of this establishment.', [], 403);
            }
            $this->imageService->deleteImage($establishmentImage->image);
            if($this->EstablishmentImageRepository->delete($id)){
                return ApiResponseClass::sendResponse([], 'Image deleted successfully.');
            }
            return ApiResponseClass::sendError('Establishment image not found', [], 404);
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Failed to delete image: ' . $e->getMessage());
        }
    }
}
