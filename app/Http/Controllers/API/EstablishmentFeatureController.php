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
            return ApiResponseClass::sendResponse($Features, 'تم جلب ميزات المنشأة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ أثناء جلب ميزات المنشأة', $e->getMessage());
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
            'icon_id' => ['required',Rule::exists('establishment_features_icons','id')],
        ]);
        try {
            $user = auth('sanctum')->user();
            $establishment= $this->EstablishmentRepository->getById($fields['establishment_id']);
            if ($establishment->owner_id != $user->id) {
                return ApiResponseClass::sendError('فقط مالك المنشأة يمكنه إضافة الميزات', null, 403);
            }
            $feature = $establishment->features()->create([
            'name' => $fields['name'],
            'description'=>$fields['description'] ?? null,
            'icon_id' => $fields['icon_id']
        ]);
            return ApiResponseClass::sendResponse($feature, 'تم حفظ ميزة المنشأة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في حفظ ميزة المنشأة: ' . $e->getMessage());
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
            'icon_id' => ['required',Rule::exists('establishment_features_icons','id')],
        ]);
        try {
            $feature = $this->EstablishmentFeatureRepository->getById($id);
            $establishment = $feature->establishment;
            if ($establishment->owner_id != auth('sanctum')->user()->id) {
                return ApiResponseClass::sendError('فقط مالك المنشأة يمكنه تحديث الميزات', null, 403);
            }
            $feature = $this->EstablishmentFeatureRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($feature,'تم تحديث ميزة المنشأة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في تحديث ميزة المنشأة: ' . $e->getMessage());
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
                return ApiResponseClass::sendError('فقط مالك المنشأة يمكنه حذف الميزات', null, 403);
            }
           if($this->EstablishmentFeatureRepository->delete($id)) {
                return ApiResponseClass::sendResponse($feature, "تم حذف الميزة رقم {$feature->id} بنجاح");
           }
           return ApiResponseClass::sendError("الميزة رقم {$id} قد لا تكون موجودة أو لم يتم حذفها. حاول مرة أخرى.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في حذف ميزة المنشأة: ' . $e->getMessage());
        }
    }
}
