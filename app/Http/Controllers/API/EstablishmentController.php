<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\EstablishmentRepository;

class EstablishmentController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private EstablishmentRepository $EstablishmentRepository,private ImageService $ImageService)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $establishments = $this->EstablishmentRepository->index($request);
            return ApiResponseClass::sendResponse($establishments, 'All establishments retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving establishments: ' . $e->getMessage());
        } 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
        'type_id' => ['required',Rule::exists('establishment_types','id')],
        'region_id' => ['required',Rule::exists('regions', 'id')->where(function ($query){return $query->where('parent_id', '!=', null);})],
        'name' => ['required','string','max:255'],
        'description' => ['nullable','string'],
        'primary_image' => ['required','image','max:2048'],
        'address'=>['required','string'],
        'images' => ['nullable', 'array'],
        'images.*' => ['image', 'max:2048'],
        'latitude' => ['nullable','numeric'],
        'longitude' => ['nullable','numeric'],
        'features' => ['nullable', 'array'],
        'features.*.name' => ['required_with:features', 'string', 'max:100'],
        'features.*.icon' => ['required', 'string', 'max:255','min:1'],
        'features.*.description' => ['nullable', 'string', 'max:255','min:1'],
        'rules' => ['nullable', 'array'],
        'rules.*' => ['string', 'max:1000'],
        'price_packages' => ['required', 'array', 'min:1'],
        'price_packages.*.name' => ['required', 'string', 'max:100'],
        'price_packages.*.description' => ['nullable', 'string', 'max:1000'],
        'price_packages.*.icon_id' => ['nullable',Rule::exists('price_package_icons','id')],
        'price_packages.*.price' => ['required', 'numeric', 'min:0'],
        'price_packages.*.features' => ['nullable', 'array'],
        'price_packages.*.features.*' => ['required', 'string', 'max:100'],
        'specifications' => ['nullable', 'array'],
        'specifications.*.name' => ['required_with:specifications', 'string', 'max:100'],
        'specifications.*.icon' => ['required_with:specifications', 'string', 'max:255'],
    ]);
    try {
        $user = auth('sanctum')->user();
        if ($user->user_type !== 'owner') {
            return ApiResponseClass::sendError('Only users with owner type can create establishments',null,403);
        }
        $fields['owner_id'] = $user->id;
        $fields['primary_image'] = $this->ImageService->saveImage($fields['primary_image'], 'establishment-image');
        $establishment = $this->EstablishmentRepository->store($fields);
        
        if ($request->hasFile('images')){
            foreach ($request->file('images') as $image){
                $imagePath = $this->ImageService->saveImage($image, 'establishment-image');
                $establishment->images()->create(['image' => $imagePath]);  
            }
        }

        if (!empty($fields['features'])) {
            foreach ($fields['features'] as $feature) {
                $establishment->features()->create($feature);
            }
        }
        
        if (!empty($fields['rules'])) {
            foreach ($fields['rules'] as $rule) {
                $establishment->rules()->create(['rule' => $rule]);
            }
        }
        if (!empty($fields['price_packages'])) {
            foreach ($fields['price_packages'] as $package) {
                $establishment->pricePackages()->create([
                    'name' => $package['name'],
                    'description' => $package['description'] ?? null,
                    'icon' => $package['icon'],
                    'price' => $package['price'],
                    'features' => !empty($package['features']) ? json_encode($package['features']) : null,
                ]);
            }
        }
        if (!empty($fields['specifications'])) {
            foreach ($fields['specifications'] as $specification) {
                $establishment->specifications()->create([
                    'name' => $specification['name'],
                    'icon' => $specification['icon'],
                ]);
            }
        }
        $establishment->load(['images', 'features', 'rules','specifications', 'type', 'region', 'owner']);
        return ApiResponseClass::sendResponse([
            'establishment' => $establishment,
            'images' => $establishment->images,
            'features' => $establishment->features,
            'rules' => $establishment->rules]
            ,'establishment saved successfully.');
    } catch (Exception $e) {
        return ApiResponseClass::sendError('Error save establishment: ' . $e->getMessage());
    }
    
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $establishment=$this->EstablishmentRepository->getById($id);
            return ApiResponseClass::sendResponse($establishment,'establishment');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error save establishment: ' . $e->getMessage());
        }
    }

    /**
 * Update the specified resource in storage.
 */
    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'type_id' => ['sometimes', 'required', Rule::exists('establishment_types', 'id')],
            'region_id' => ['sometimes', 'required', Rule::exists('regions', 'id')->where(function ($query) {return $query->where('parent_id', '!=', null);})],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'primary_image' => ['sometimes', 'image', 'max:2048'],
            'address' => ['sometimes', 'required', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'is_active' => ['sometimes', 'boolean'],
            'features' => ['nullable', 'array'],
            'features.*.id' => ['sometimes', 'numeric'], 
            'features.*.name' => ['required_with:features', 'string', 'max:100'],
            'features.*.icon' => ['required', 'string', 'max:255', 'min:1'],
            'features.*.description' => ['nullable', 'string', 'max:255', 'min:1'],
            'deleted_features' => ['nullable', 'array'],
            'deleted_features.*' => ['numeric'],
            'rules' => ['nullable', 'array'],
            'rules.*.id' => ['sometimes', 'numeric'], 
            'rules.*.rule' => ['required', 'string', 'max:1000'],
            'deleted_rules' => ['nullable', 'array'],
            'deleted_rules.*' => ['numeric'],
            'price_packages' => ['sometimes', 'array', 'min:1'],
            'price_packages.*.id' => ['sometimes', 'numeric'], 
            'price_packages.*.name' => ['required', 'string', 'max:100'],
            'price_packages.*.description' => ['nullable', 'string', 'max:1000'],
            'price_packages.*.icon_id' => ['nullable', Rule::exists('price_package_icons', 'id')],
            'price_packages.*.price' => ['required', 'numeric', 'min:0'],
            'price_packages.*.features' => ['nullable', 'array'],
            'price_packages.*.features.*' => ['required', 'string', 'max:100'],
            'deleted_price_packages' => ['nullable', 'array'],
            'deleted_price_packages.*' => ['numeric'],
            'specifications' => ['nullable', 'array'],
            'specifications.*.id' => ['sometimes', 'numeric'], 
            'specifications.*.name' => ['required_with:specifications', 'string', 'max:100'],
            'specifications.*.icon' => ['required_with:specifications', 'string', 'max:255'],
            'deleted_specifications' => ['nullable', 'array'],
            'deleted_specifications.*' => ['numeric'],
        ]);

        try {
            $user = auth('sanctum')->user();
            $establishment = $this->EstablishmentRepository->getById($id);
            if ($establishment->owner_id !== $user->id) {
                return ApiResponseClass::sendError('You are not authorized to update this establishment', null, 403);
            }
            if ($request->hasFile('primary_image')) {
                $this->ImageService->deleteImage($establishment->primary_image);
                $fields['primary_image'] = $this->ImageService->saveImage($fields['primary_image'], 'establishment-image');
            } else {
                unset($fields['primary_image']);
            }
            $establishment = $this->EstablishmentRepository->update($fields, $id);


            if (isset($fields['features'])) {
                foreach ($fields['features'] as $feature) {
                    if (isset($feature['id'])) {
                        $establishment->features()->where('id', $feature['id'])->update($feature);
                    } else {
                        $establishment->features()->create($feature);
                    }
                }
            }
            if (!empty($fields['deleted_features'])) {
                $establishment->features()->whereIn('id', $fields['deleted_features'])->delete();
            }

            if (isset($fields['rules'])) {
                foreach ($fields['rules'] as $rule) {
                    if (isset($rule['id'])) {
                        $establishment->rules()->where('id', $rule['id'])->update(['rule' => $rule['rule']]);
                    } else {
                        $establishment->rules()->create(['rule' => $rule['rule']]);
                    }
                }
            }
            if (!empty($fields['deleted_rules'])) {
                $establishment->rules()->whereIn('id', $fields['deleted_rules'])->delete();
            }

        
            if (isset($fields['price_packages'])) {
                foreach ($fields['price_packages'] as $package) {
                    $packageData = [
                        'name' => $package['name'],
                        'description' => $package['description'] ?? null,
                        'icon' => $package['icon'],
                        'price' => $package['price'],
                        'features' => !empty($package['features']) ? json_encode($package['features']) : null,
                    ];
                    if (isset($package['id'])) {
                        $establishment->pricePackages()->where('id', $package['id'])->update($packageData);
                    } else {
                        $establishment->pricePackages()->create($packageData);
                    }
                }
            }
            if (!empty($fields['deleted_price_packages'])) {
                $establishment->pricePackages()->whereIn('id', $fields['deleted_price_packages'])->delete();
            }

            if (isset($fields['specifications'])) {
                foreach ($fields['specifications'] as $specification) {
                    if (isset($specification['id'])) {
                        
                        $establishment->specifications()->where('id', $specification['id'])->update([
                            'name' => $specification['name'],
                            'icon' => $specification['icon'],
                        ]);
                    } else {
                        $establishment->specifications()->create([
                            'name' => $specification['name'],
                            'icon' => $specification['icon'],
                        ]);
                    }
                }
            }

            if (!empty($fields['deleted_specifications'])) {
                $establishment->specifications()->whereIn('id', $fields['deleted_specifications'])->delete();
            }

            $establishment->load(['images', 'features', 'rules', 'specifications', 'type', 'region', 'owner', 'pricePackages']);

            return ApiResponseClass::sendResponse([
                'establishment' => $establishment,
                'images' => $establishment->images,
                'features' => $establishment->features,
                'rules' => $establishment->rules,
                'price_packages' => $establishment->pricePackages,
                'specifications' => $establishment->specifications
            ], 'Establishment updated successfully.');

        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error updating establishment: ' . $e->getMessage(), null, 500);
        }
    }

    /**
    * Remove the specified resource from storage.
    */
    public function destroy(string $id)
    {
        try {
            $user = auth('sanctum')->user();
            $establishment = $this->EstablishmentRepository->getById($id);
            if ($establishment->owner_id !== $user->id && $user->user_type !== 'admin') {
                return ApiResponseClass::sendError('You are not authorized to delete this establishment', null, 403);
            }
            $this->deleteEstablishmentImages($establishment);
            $this->EstablishmentRepository->delete($id);
            return ApiResponseClass::sendResponse(null, 'Establishment deleted successfully');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting establishment: ' . $e->getMessage(), null, 500);
        }
    }

    private function deleteEstablishmentImages($establishment)
    {
        if ($establishment->primary_image) {
            $this->ImageService->deleteImage($establishment->primary_image);
        }
        foreach ($establishment->images as $image) {
            $this->ImageService->deleteImage($image->image);
        }
    }
}
