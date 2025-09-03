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
        'policy'=>['required','string'],
        'features' => ['nullable', 'array'],
        'features.*.name' => ['required_with:features', 'string', 'max:100'],
        'features.*.icon_id' => ['required', Rule::exists('establishment_features_icons','id')],
        'features.*.description' => ['nullable', 'string', 'max:255','min:1'],
        'rules' => ['nullable', 'array'],
        'rules.*' => ['string', 'max:1000'],
        'price_packages' => ['required', 'array', 'min:1'],
        'price_packages.*.name' => ['required', 'string', 'max:100'],
        'price_packages.*.description' => ['nullable', 'string', 'max:1000'],
        'price_packages.*.icon_id' => ['nullable',Rule::exists('price_package_icons','id')],
        'price_packages.*.price' => ['required', 'numeric', 'min:0'],
        'price_packages.*.currency_id' => ['required', Rule::exists('currencies', 'id')],
        'price_packages.*.time_period' => ['nullable', 'string', 'in:morning,evening,any'],
        'price_packages.*.features' => ['nullable', 'array'],
        'price_packages.*.features.*' => ['required', 'string', 'max:100'],
        'specifications' => ['nullable', 'array'],
        'specifications.*.name' => ['required_with:specifications', 'string', 'max:100'],
        'specifications.*.icon' => ['required_with:specifications', 'string', 'max:255'],
    ],[
        'type_id.required' => 'حقل نوع المنشأة مطلوب.',
        'type_id.exists' => 'نوع المنشأة المحدد غير صالح.',
        'region_id.required' => 'حقل المنطقة مطلوب.',
        'region_id.exists' => 'المنطقة المحددة غير صالحة.',
        'name.required' => 'حقل الاسم مطلوب.',
        'name.string' => 'يجب أن يكون الاسم نصًا.',
        'name.max' => 'يجب ألا يتجاوز الاسم 255 حرفًا.',
        'description.string' => 'يجب أن يكون الوصف نصًا.',
        'primary_image.required' => 'الصورة الرئيسية مطلوبة.',
        'primary_image.image' => 'الصورة الرئيسية يجب أن تكون ملف صورة.',
        'primary_image.max' => 'يجب ألا يتجاوز حجم الصورة الرئيسية 2048 كيلوبايت.',
        'address.required' => 'حقل العنوان مطلوب.',
        'address.string' => 'يجب أن يكون العنوان نصًا.',
        'images.array' => 'يجب أن تكون الصور مصفوفة.',
        'images.*.image' => 'يجب أن يكون كل ملف في حقل الصور ملف صورة.',
        'images.*.max' => 'يجب ألا يتجاوز حجم كل صورة 2048 كيلوبايت.',
        'latitude.numeric' => 'يجب أن يكون خط العرض رقمًا.',
        'longitude.numeric' => 'يجب أن يكون خط الطول رقمًا.',
        'policy.required' => 'حقل السياسة مطلوب.',
        'policy.string' => 'يجب أن تكون السياسة نصًا.',
        'features.array' => 'يجب أن تكون الميزات مصفوفة.',
        'features.*.name.required_with' => 'اسم الميزة مطلوب عند وجود الميزات.',
        'features.*.name.string' => 'يجب أن يكون اسم الميزة نصًا.',
        'features.*.name.max' => 'يجب ألا يتجاوز اسم الميزة 100 حرفًا.',
        'features.*.icon_id.required' => 'معرف أيقونة الميزة مطلوب.',
        'features.*.icon_id.exists' => 'معرف أيقونة الميزة المحدد غير صالح.',
        'features.*.description.string' => 'يجب أن يكون وصف الميزة نصًا.',
        'features.*.description.max' => 'يجب ألا يتجاوز وصف الميزة 255 حرفًا.',
        'features.*.description.min' => 'يجب أن يكون وصف الميزة على الأقل 1 حرف.',
        'rules.array' => 'يجب أن تكون القواعد مصفوفة.',
        'rules.*.string' => 'يجب أن تكون كل قاعدة نصًا.',
        'rules.*.max' => 'يجب ألا تتجاوز كل قاعدة 1000 حرفًا.',
        'price_packages.required' => 'حقل باقات الأسعار مطلوب.',
        'price_packages.array' => 'يجب أن تكون باقات الأسعار مصفوفة.',
        'price_packages.min' => 'يجب أن تحتوي باقات الأسعار على عنصر واحد على الأقل.',
        'price_packages.*.name.required' => 'اسم الباقة مطلوب.',
        'price_packages.*.name.string' => 'يجب أن يكون اسم الباقة نصًا.',
        'price_packages.*.name.max' => 'يجب ألا يتجاوز اسم الباقة 100 حرفًا.',
        'price_packages.*.description.string' => 'يجب أن يكون وصف الباقة نصًا.',
        'price_packages.*.description.max' => 'يجب ألا يتجاوز وصف الباقة 1000 حرفًا.',
        'price_packages.*.icon_id.exists' => 'معرف أيقونة الباقة المحدد غير صالح.',
        'price_packages.*.price.required' => 'سعر الباقة مطلوب.',
        'price_packages.*.price.numeric' => 'يجب أن يكون السعر رقمًا.',
        'price_packages.*.price.min' => 'يجب أن يكون السعر 0 على الأقل.',
        'price_packages.*.currency_id.required' => 'معرف العملة مطلوب.',
        'price_packages.*.currency_id.exists' => 'معرف العملة المحدد غير صالح.',
        'price_packages.*.time_period.string' => 'يجب أن تكون الفترة الزمنية نصًا.',
        'price_packages.*.time_period.in' => 'الفترة الزمنية المحددة غير صالحة.',
        'price_packages.*.features.array' => 'يجب أن تكون ميزات الباقة مصفوفة.',
        'price_packages.*.features.*.required' => 'ميزة الباقة مطلوبة.',
        'price_packages.*.features.*.string' => 'يجب أن تكون ميزة الباقة نصًا.',
        'price_packages.*.features.*.max' => 'يجب ألا تتجاوز ميزة الباقة 100 حرفًا.',
        'specifications.array' => 'يجب أن تكون المواصفات مصفوفة.',
        'specifications.*.name.required_with' => 'اسم المواصفات مطلوب عند وجود المواصفات.',
        'specifications.*.name.string' => 'يجب أن يكون اسم المواصفات نصًا.',
        'specifications.*.name.max' => 'يجب ألا يتجاوز اسم المواصفات 100 حرفًا.',
        'specifications.*.icon.required_with' => 'أيقونة المواصفات مطلوبة عند وجود المواصفات.',
        'specifications.*.icon.string' => 'يجب أن تكون أيقونة المواصفات نصًا.',
        'specifications.*.icon.max' => 'يجب ألا تتجاوز أيقونة المواصفات 255 حرفًا.',
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
                $establishment->features()->create([
                    'name' => $feature['name'],
                    'description' => $feature['description'] ?? null,
                    'icon_id' => $feature['icon_id']
                ]);
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
                    'icon_id' => $package['icon_id'],
                    'price' => $package['price'],
                    'currency_id' => $package['currency_id'],
                    'time_period' => $package['time_period'] ?? 'any',
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
            ,'تم حفظ المنشأة بنجاح');
    } catch (Exception $e) {
        return ApiResponseClass::sendError('حدث خطأ في حفظ المنشأة: ' . $e->getMessage());
    }
    
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $establishment=$this->EstablishmentRepository->getById($id);
            return ApiResponseClass::sendResponse($establishment, 'تم جلب بيانات المنشأة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في جلب بيانات المنشأة: ' . $e->getMessage());
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
            'policy'=>['sometimes','string'],
            'is_active' => ['sometimes', 'boolean'],
            'features' => ['nullable', 'array'],
            'features.*.id' => ['sometimes', 'numeric'], 
            'features.*.name' => ['required_with:features', 'string', 'max:100'],
            'features.*.icon_id' => ['required', Rule::exists('establishment_features_icons','id')],
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
            'price_packages.*.currency_id' => ['required', Rule::exists('currencies', 'id')],
            'price_packages.*.time_period' => ['nullable', 'string', 'in:morning,evening,any'],
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
        ], [
            'type_id.sometimes' => 'حقل نوع المنشأة اختياري.',
            'type_id.required' => 'حقل نوع المنشأة مطلوب.',
            'type_id.exists' => 'نوع المنشأة المحدد غير صالح.',
            'region_id.sometimes' => 'حقل المنطقة اختياري.',
            'region_id.required' => 'حقل المنطقة مطلوب.',
            'region_id.exists' => 'المنطقة المحددة غير صالحة.',
            'name.sometimes' => 'حقل الاسم اختياري.',
            'name.required' => 'حقل الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'يجب ألا يتجاوز الاسم 255 حرفًا.',
            'description.string' => 'يجب أن يكون الوصف نصًا.',
            'primary_image.sometimes' => 'الصورة الرئيسية اختيارية.',
            'primary_image.image' => 'الصورة الرئيسية يجب أن تكون ملف صورة.',
            'primary_image.max' => 'يجب ألا يتجاوز حجم الصورة الرئيسية 2048 كيلوبايت.',
            'address.sometimes' => 'حقل العنوان اختياري.',
            'address.required' => 'حقل العنوان مطلوب.',
            'address.string' => 'يجب أن يكون العنوان نصًا.',
            'latitude.numeric' => 'يجب أن يكون خط العرض رقمًا.',
            'longitude.numeric' => 'يجب أن يكون خط الطول رقمًا.',
            'policy.sometimes' => 'حقل السياسة اختياري.',
            'policy.string' => 'يجب أن تكون السياسة نصًا.',
            'is_active.sometimes' => 'حقل "هل هو نشط؟" اختياري.',
            'is_active.boolean' => 'يجب أن تكون قيمة "هل هو نشط؟" صحيحة أو خاطئة.',
            'features.array' => 'يجب أن تكون الميزات مصفوفة.',
            'features.*.id.sometimes' => 'معرف الميزة اختياري.',
            'features.*.id.numeric' => 'يجب أن يكون معرف الميزة رقمًا.',
            'features.*.name.required_with' => 'اسم الميزة مطلوب عند وجود الميزات.',
            'features.*.name.string' => 'يجب أن يكون اسم الميزة نصًا.',
            'features.*.name.max' => 'يجب ألا يتجاوز اسم الميزة 100 حرفًا.',
            'features.*.icon_id.required' => 'معرف أيقونة الميزة مطلوب.',
            'features.*.icon_id.exists' => 'معرف أيقونة الميزة المحدد غير صالح.',
            'features.*.description.string' => 'يجب أن يكون وصف الميزة نصًا.',
            'features.*.description.max' => 'يجب ألا يتجاوز وصف الميزة 255 حرفًا.',
            'features.*.description.min' => 'يجب أن يكون وصف الميزة على الأقل 1 حرف.',
            'deleted_features.array' => 'يجب أن تكون الميزات المحذوفة مصفوفة.',
            'deleted_features.*.numeric' => 'يجب أن يكون معرف الميزة المحذوفة رقمًا.',
            'rules.array' => 'يجب أن تكون القواعد مصفوفة.',
            'rules.*.id.sometimes' => 'معرف القاعدة اختياري.',
            'rules.*.id.numeric' => 'يجب أن يكون معرف القاعدة رقمًا.',
            'rules.*.rule.required' => 'حقل القاعدة مطلوب.',
            'rules.*.rule.string' => 'يجب أن تكون القاعدة نصًا.',
            'rules.*.rule.max' => 'يجب ألا تتجاوز القاعدة 1000 حرفًا.',
            'deleted_rules.array' => 'يجب أن تكون القواعد المحذوفة مصفوفة.',
            'deleted_rules.*.numeric' => 'يجب أن يكون معرف القاعدة المحذوفة رقمًا.',
            'price_packages.sometimes' => 'حقل باقات الأسعار اختياري.',
            'price_packages.array' => 'يجب أن تكون باقات الأسعار مصفوفة.',
            'price_packages.min' => 'يجب أن تحتوي باقات الأسعار على عنصر واحد على الأقل.',
            'price_packages.*.id.sometimes' => 'معرف الباقة اختياري.',
            'price_packages.*.id.numeric' => 'يجب أن يكون معرف الباقة رقمًا.',
            'price_packages.*.name.required' => 'اسم الباقة مطلوب.',
            'price_packages.*.name.string' => 'يجب أن يكون اسم الباقة نصًا.',
            'price_packages.*.name.max' => 'يجب ألا يتجاوز اسم الباقة 100 حرفًا.',
            'price_packages.*.description.string' => 'يجب أن يكون وصف الباقة نصًا.',
            'price_packages.*.description.max' => 'يجب ألا يتجاوز وصف الباقة 1000 حرفًا.',
            'price_packages.*.icon_id.exists' => 'معرف أيقونة الباقة المحدد غير صالح.',
            'price_packages.*.price.required' => 'سعر الباقة مطلوب.',
            'price_packages.*.price.numeric' => 'يجب أن يكون السعر رقمًا.',
            'price_packages.*.price.min' => 'يجب أن يكون السعر 0 على الأقل.',
            'price_packages.*.currency_id.required' => 'معرف العملة مطلوب.',
            'price_packages.*.currency_id.exists' => 'معرف العملة المحدد غير صالح.',
            'price_packages.*.time_period.string' => 'يجب أن تكون الفترة الزمنية نصًا.',
            'price_packages.*.time_period.in' => 'الفترة الزمنية المحددة غير صالحة.',
            'price_packages.*.features.array' => 'يجب أن تكون ميزات الباقة مصفوفة.',
            'price_packages.*.features.*.required' => 'ميزة الباقة مطلوبة.',
            'price_packages.*.features.*.string' => 'يجب أن تكون ميزة الباقة نصًا.',
            'price_packages.*.features.*.max' => 'يجب ألا تتجاوز ميزة الباقة 100 حرفًا.',
            'deleted_price_packages.array' => 'يجب أن تكون باقات الأسعار المحذوفة مصفوفة.',
            'deleted_price_packages.*.numeric' => 'يجب أن يكون معرف باقة السعر المحذوفة رقمًا.',
            'specifications.array' => 'يجب أن تكون المواصفات مصفوفة.',
            'specifications.*.id.sometimes' => 'معرف المواصفات اختياري.',
            'specifications.*.id.numeric' => 'يجب أن يكون معرف المواصفات رقمًا.',
            'specifications.*.name.required_with' => 'اسم المواصفات مطلوب عند وجود المواصفات.',
            'specifications.*.name.string' => 'يجب أن يكون اسم المواصفات نصًا.',
            'specifications.*.name.max' => 'يجب ألا يتجاوز اسم المواصفات 100 حرفًا.',
            'specifications.*.icon.required_with' => 'أيقونة المواصفات مطلوبة عند وجود المواصفات.',
            'specifications.*.icon.string' => 'يجب أن تكون أيقونة المواصفات نصًا.',
            'specifications.*.icon.max' => 'يجب ألا تتجاوز أيقونة المواصفات 255 حرفًا.',
            'deleted_specifications.array' => 'يجب أن تكون المواصفات المحذوفة مصفوفة.',
            'deleted_specifications.*.numeric' => 'يجب أن يكون معرف المواصفات المحذوفة رقمًا.',
        ]);

        try {
            $user = auth('sanctum')->user();
            $establishment = $this->EstablishmentRepository->getById($id);
            if ($establishment->owner_id !== $user->id) {
                return ApiResponseClass::sendError('غير مصرح لك بتحديث هذه المنشأة', null, 403);
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
                        'icon_id' => $package['icon_id'],
                        'price' => $package['price'],
                        'currency_id' => $package['currency_id'],
                        'time_period' => $package['time_period'] ?? 'any',
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
            ], 'تم تحديث المنشأة بنجاح');

        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في تحديث المنشأة: ' . $e->getMessage(), null, 500);
        }
    }

    /**
    * Remove the specified resource from storage.
    */
    public function destroy(string $id)
    {
        try {
            $user = auth('sanctum')->user();
            $establishment = $this->EstablishmentRepository->find($id);
            if ($establishment->owner_id !== $user->id && $user->user_type !== 'admin') {
                return ApiResponseClass::sendError('غير مصرح لك بحذف هذه المنشأة', null, 403);
            }
            $this->deleteEstablishmentImages($establishment);
            $this->EstablishmentRepository->delete($id);
            return ApiResponseClass::sendResponse(null, 'تم حذف المنشأة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في حذف المنشأة: ' . $e->getMessage(), null, 500);
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
