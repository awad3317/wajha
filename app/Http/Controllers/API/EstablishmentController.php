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
