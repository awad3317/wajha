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
            return ApiResponseClass::sendResponse($Accounts, 'All accounts retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving accounts: ' . $e->getMessage());
        } 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth('sanctum')->user()->user_type !== 'owner') {
            return ApiResponseClass::sendError('Unauthorized: Only Owners can create establishment types', [], 403);
        }
        $fields=$request->validate([
            'bank_id'=>['required',Rule::exists('banks','id')],
            'account_number'=>['required','string','min:7','max:15'],
        ]);
        try {
            $fields['owner_id'] = auth( 'sanctum')->id();
            $account = $this->OwnerAccountRepository->store($fields);
            return ApiResponseClass::sendResponse($account, 'account saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error save account: ' . $e->getMessage());
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
        if($id != auth('sanctum')->id()){
            return ApiResponseClass::sendError('You are not authorized to update this acount.', [], 403);
        }
        $fields=$request->validate([
            'bank_id'=>['sometimes',Rule::exists('banks','id')],
            'account_number'=>['sometimes','string','min:7','max:15'],
        ]);
        try {
            $account = $this->OwnerAccountRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($account, 'account update successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error update account: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if($id != auth('sanctum')->id()){
            return ApiResponseClass::sendError('You are not authorized to deleting this acount.', [], 403);
        }
        try {
            $account=$this->OwnerAccountRepository->getById($id);
            if($this->OwnerAccountRepository->delete($id)){
                return ApiResponseClass::sendResponse($account, "{$account->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("Acount with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting acount: ' . $e->getMessage());
        }
    }
}
