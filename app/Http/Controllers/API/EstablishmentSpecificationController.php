<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Classes\ApiResponseClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Repositories\EstablishmentRepository;
use App\Repositories\EstablishmentSpecificationRepository;

class EstablishmentSpecificationController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private EstablishmentSpecificationRepository $EstablishmentSpecificationRepository,private EstablishmentRepository $EstablishmentRepository)
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
        $fields = $request->validate([
            'establishment_id' => ['required',Rule::exists('establishments','id')],
            'name' => ['required','string','max:100'],
            'icon'=>['required','string','max:100']
        ], [
            'establishment_id.required' => 'يجب اختيار المنشئة.',
            'establishment_id.exists' => 'المنشئة المحددة غير موجودة.',
            'name.required' => 'يجب إدخال اسم.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'لا يجب أن يتجاوز الاسم 100 حرف.',
            'icon.required' => 'يجب إدخال اسم الأيقونة.',
            'icon.string' => 'يجب أن يكون اسم الأيقونة نصًا.',
            'icon.max' => 'لا يجب أن يتجاوز اسم الأيقونة 100 حرف.',
        ]);
        try {
            $user = auth('sanctum')->user();
            $establishment= $this->EstablishmentRepository->getById($fields['establishment_id']);
            if ($establishment->owner_id != $user->id) {
                return ApiResponseClass::sendError('فقط مالك المنشأة يمكنه إضافة المواصفات', null, 403);
            }
            $specification = $this->EstablishmentSpecificationRepository->store($fields);
            return ApiResponseClass::sendResponse($specification, 'تم حفظ مواصفات المنشأة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في حفظ مواصفات المنشأة: ' . $e->getMessage());
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
