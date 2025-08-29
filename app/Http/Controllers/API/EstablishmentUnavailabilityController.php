<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\EstablishmentRepository;
use App\Repositories\EstablishmentUnavailabilityRepository;

class EstablishmentUnavailabilityController extends Controller
{
      /**
     * Create a new class instance.
     */
    public function __construct(private EstablishmentUnavailabilityRepository $EstablishmentUnavailabilityRepository,private EstablishmentRepository $EstablishmentRepository)
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
            'unavailable_date' => ['required','date'],
        ]);
        try {
            $user = auth('sanctum')->user();
            $establishment= $this->EstablishmentRepository->getById($fields['establishment_id']);
            if ($establishment->owner_id != $user->id) {
                return ApiResponseClass::sendError('فقط مالك المنشأة يمكنه إضافة التواريخ غير المتاحة', null, 403);
            }
            $unavailable_date = $this->EstablishmentUnavailabilityRepository->store($fields);
            return ApiResponseClass::sendResponse($unavailable_date, 'تم حفظ التاريخ غير المتاح للمنشأة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في حفظ التاريخ غير المتاح للمنشأة: ' . $e->getMessage());
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
        try {
            $user = auth('sanctum')->user();
            $unavailable_date=$this->EstablishmentUnavailabilityRepository->getById($id);
            $establishment=$unavailable_date->establishment;
            if ($establishment->owner_id != $user->id) {
                return ApiResponseClass::sendError('فقط مالك المنشأة يمكنه حذف التواريخ غير المتاحة', null, 403);
            }
            if($this->EstablishmentUnavailabilityRepository->delete($id)){
                return ApiResponseClass::sendResponse($unavailable_date, "تم حذف التاريخ غير المتاح رقم {$unavailable_date->id} بنجاح");
            }
            return ApiResponseClass::sendError("التاريخ غير المتاح رقم {$id} قد لا يكون موجوداً أو لم يتم حذفه. حاول مرة أخرى.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في حذف التاريخ غير المتاح: ' . $e->getMessage());
        }
    }
}
