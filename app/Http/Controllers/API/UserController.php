<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

     /**
     * Create a new class instance.
     */
    public function __construct(private UserRepository $UserRepository)
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
        //
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
            return ApiResponseClass::sendError('You are not authorized to update this user.', [], 403);
        }
        $fields=$request->validate([
            'name'=>['sometimes','string','max:100'],
            'phone'=>['sometimes','string','min:9','max:15',Rule::unique('users')->ignore($id)],
            'current_password' => ['sometimes', 'required_with:new_password', 'string'],
            'new_password' => ['sometimes', 'required_with:current_password', 'string', 'min:6', 'confirmed']
        ]);
        try {
            if ($request->has('current_password') && $request->has('new_password')){
                if (!Hash::check($request->current_password, auth('sanctum')->user()->password)) {
                    return ApiResponseClass::sendError('Current password is incorrect.', [], 422);
                }
                $fields['password'] =$request->new_password;
                unset($fields['current_password'], $fields['new_password']);
            }
            $user=$this->UserRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($user,'User is updated successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error updated User: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
