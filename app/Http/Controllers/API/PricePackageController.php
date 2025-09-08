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
            'currency_id' => ['required', Rule::exists('currencies', 'id')],
            'time_period' => ['nullable', 'string', 'in:morning,evening,any'],
            'features' => ['nullable', 'array'],
            'features.*' => ['required', 'string', 'max:100'],
        ], [
            'establishment_id.required' => 'يجب اختيار منشأة.',
            'establishment_id.exists' => 'المنشأة غير موجودة.',
            'icon_id.exists' => 'الأيقونة غير موجودة.',
            'name.required' => 'يجب إدخال اسم.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'لا يجب أن يتجاوز الاسم 100 حرف.',
            'description.string' => 'يجب أن يكون الوصف نصًا.',
            'description.max' => 'لا يجب أن يتجاوز الوصف 1000 حرف.',
            'price.required' => 'يجب إدخال سعر.',
            'price.numeric' => 'يجب أن يكون السعر رقمًا.',
            'price.min' => 'يجب أن يكون السعر أكبر من أو يساوي الصفر.',
            'currency_id.required' => 'يجب اختيار عملة.',
            'currency_id.exists' => 'العملة غير موجودة.',
            'time_period.in' => 'فترة الوقت المحددة غير مسموحة.',
            'features.array' => 'يجب أن تكون الميزات على شكل مصفوفة.',
            'features.*.required' => 'حقل الميزة مطلوب.',
            'features.*.string' => 'يجب أن تكون الميزة نصًا.',
            'features.*.max' => 'لا يجب أن تتجاوز الميزة 100 حرف.',
        ]);

        try {
            $userId= auth('sanctum')->id();
            $establishment=$this->EstablishmentRepository->getById($fields['establishment_id']);
            if (!$establishment || $establishment->owner_id !== $userId) {
                return ApiResponseClass::sendError('غير مصرح. ليس لديك صلاحية إضافة باقة أسعار إلى هذه المنشأة.', [], 403);
            }
            $fields['time_period'] = $fields['time_period'] ?? 'any';
            $package=$this->PricePackageRepository->store($fields);
            return ApiResponseClass::sendResponse($package, 'تم إنشاء باقة الأسعار بنجاح مع الميزات  ');

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
            'currency_id' => ['required', Rule::exists('currencies', 'id')],
            'time_period' => ['sometimes', 'string', 'in:morning,evening,any'],
            'features' => ['nullable', 'array'],
            'features.*' => ['required_with:features', 'string', 'max:100'],
        ], [
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'لا يجب أن يتجاوز الاسم 100 حرف.',
            'description.string' => 'يجب أن يكون الوصف نصًا.',
            'description.max' => 'لا يجب أن يتجاوز الوصف 1000 حرف.',
            'icon_id.exists' => 'الأيقونة غير موجودة.',
            'price.numeric' => 'يجب أن يكون السعر رقمًا.',
            'price.min' => 'يجب أن يكون السعر أكبر من أو يساوي الصفر.',
            'currency_id.required' => 'يجب اختيار عملة.',
            'currency_id.exists' => 'العملة غير موجودة.',
            'time_period.in' => 'فترة الوقت المحددة غير مسموحة.',
            'features.array' => 'يجب أن تكون الميزات على شكل مصفوفة.',
            'features.*.required_with' => 'حقل الميزة مطلوب عند إرسال الميزات.',
            'features.*.string' => 'يجب أن تكون الميزة نصًا.',
            'features.*.max' => 'لا يجب أن تتجاوز الميزة 100 حرف.',
        ]);
        try {
            $userId = auth('sanctum')->id();
            $package = $this->PricePackageRepository->getById($id);
            $establishment = $package->establishment;
            if (!$establishment || $establishment->owner_id !== $userId) {
            return ApiResponseClass::sendError('غير مصرح. ليس لديك صلاحية تحديث باقة الأسعار هذه.', [], 403);
        }
            $updatedPackage = $this->PricePackageRepository->update($fields, $id);
            return ApiResponseClass::sendResponse($updatedPackage, 'تم تحديث باقة الأسعار بنجاح.');
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
                return ApiResponseClass::sendError('غير مصرح. ليس لديك صلاحية حذف باقة الأسعار هذه.', [], 403);
            }
            if($this->PricePackageRepository->delete($id)){
                return ApiResponseClass::sendResponse($package, "تم حذف باقة الأسعار رقم {$package->id} بنجاح.");
            }
            return ApiResponseClass::sendError("قد لا تكون الباقة بالرقم {$id} موجودة أو لم يتم حذفها. حاول مرة أخرى.");

        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting package: ' . $e->getMessage());
        }
    }
}
