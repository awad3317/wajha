<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\EstablishmentRepository;
use App\Repositories\EstablishmentRuleRepository;

class EstablishmentRuleController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private EstablishmentRuleRepository $EstablishmentRuleRepository,private EstablishmentRepository $EstablishmentRepository)
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
            'rule' => ['required','string'],
        ],[
            'establishment_id.required' => 'حقل المنشئة مطلوب.',
            'establishment_id.exists' => 'المنشئة المحددة غير صحيحة.',
            'rule.required' => 'يجب كتابة النص الخاص بالقاعدة.',
        ]);
        try {
            $user = auth('sanctum')->user();
            $establishment= $this->EstablishmentRepository->getById($fields['establishment_id']);
            if ($establishment->owner_id != $user->id) {
                return ApiResponseClass::sendError('فقط مالك المنشأة يمكنه إضافة القواعد', null, 403);
            }
            $rule = $establishment->rules->create(['rule'=>$fields['rule']]);
             return ApiResponseClass::sendResponse($rule, 'تم حفظ قاعدة المنشأة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في حفظ قاعدة المنشأة: ' . $e->getMessage());
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
            'establishment_id' => ['required',Rule::exists('establishments','id')],
            'rule' => ['required','string'],
        ],[
            'establishment_id.required' => 'حقل المنشئة مطلوب.',
            'establishment_id.exists' => 'المنشئة المحددة غير صحيحة.',
            'rule.required' => 'يجب كتابة النص الخاص بالقاعدة.',
        ]);
        try {
            $user = auth('sanctum')->user();
            $establishment= $this->EstablishmentRepository->getById($fields['establishment_id']);
            if ($establishment->owner_id != $user->id) {
                return ApiResponseClass::sendError('فقط مالك المنشأة يمكنه تحديث القواعد', null, 403);
            }
            $rule = $this->EstablishmentRuleRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($rule, 'تم تحديث قاعدة المنشأة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في تحديث قاعدة المنشأة: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = auth('sanctum')->user();
            $rule=$this->EstablishmentRuleRepository->getById($id);
            $establishment=$rule->establishment;
            if ($establishment->owner_id != $user->id) {
                return ApiResponseClass::sendError('فقط مالك المنشأة يمكنه حذف القواعد', null, 403);
            }
            if($this->EstablishmentRuleRepository->delete($id)){
                return ApiResponseClass::sendResponse($rule, $rule, "تم حذف القاعدة رقم {$rule->id} بنجاح");
            }
            return ApiResponseClass::sendError("القاعدة رقم {$id} قد لا تكون موجودة أو لم يتم حذفها. حاول مرة أخرى.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في حذف القاعدة: ' . $e->getMessage());
        }
    }
}
