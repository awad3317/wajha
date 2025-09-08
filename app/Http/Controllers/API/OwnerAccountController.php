<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\OwnerAccountRepository;

class OwnerAccountController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private OwnerAccountRepository $OwnerAccountRepository)
    {
        //
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $Accounts = $this->OwnerAccountRepository->index();
            return ApiResponseClass::sendResponse($Accounts, 'تم جلب جميع الحسابات بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في جلب الحسابات: ' . $e->getMessage());
        } 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth('sanctum')->user()->user_type !== 'owner') {
            return ApiResponseClass::sendError('غير مصرح: فقط الملاك يمكنهم إنشاء حسابات', [], 403);
        }
        $fields=$request->validate([
            'bank_id'=>['required',Rule::exists('banks','id')],
            'account_number'=>['required','string','min:7','max:20'],
        ], [
            'bank_id.required' => 'يجب اختيار بنك.',
            'bank_id.exists' => 'البنك المحدد غير موجود.',
            'account_number.required' => 'يجب إدخال رقم الحساب.',
            'account_number.min' => 'رقم الحساب يجب أن يكون 7 أحرف على الأقل.',
            'account_number.max' => 'رقم الحساب لا يجب أن يتجاوز 20 حرفًا.',
        ]);
        try {
            $fields['owner_id'] = auth( 'sanctum')->id();
            $account = $this->OwnerAccountRepository->store($fields);
            return ApiResponseClass::sendResponse($account, 'تم حفظ الحساب بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ في حفظ الحساب: ' . $e->getMessage());
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
        $fields=$request->validate([
            'bank_id'=>['sometimes',Rule::exists('banks','id')],
            'account_number'=>['sometimes','string','min:7','max:20'],
        ], [
            'bank_id.exists' => 'البنك المحدد غير موجود.',
            'account_number.min' => 'رقم الحساب يجب أن يكون 7 أحرف على الأقل.',
            'account_number.max' => 'رقم الحساب لا يجب أن يتجاوز 20 حرفًا.',
        ]);
        try {
            $account = $this->OwnerAccountRepository->getById($id);
            if($account->owner_id != auth('sanctum')->id()) {
                return ApiResponseClass::sendError('غير مصرح لك بتحديث هذا الحساب.', [], 403);
            }
            $account = $this->OwnerAccountRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($account, 'تم تحديث الحساب بنجاح.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error update account: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $account=$this->OwnerAccountRepository->getById($id);
            if($account->owner_id != auth('sanctum')->id()){
                return ApiResponseClass::sendError('غير مصرح لك بحذف هذا الحساب.', [], 403);
            }
            if($this->OwnerAccountRepository->delete($id)){
                return ApiResponseClass::sendResponse($account, "تم إلغاء حفظ الحساب رقم {$account->id} بنجاح.");
            }
            return ApiResponseClass::sendError("قد لا يكون الحساب بالرقم {$id} موجودًا أو لم يتم حذفه. حاول مرة أخرى.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting acount: ' . $e->getMessage());
        }
    }
}
