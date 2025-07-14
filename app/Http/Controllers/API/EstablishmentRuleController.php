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
        ]);
        try {
            $user = auth('sanctum')->user();
            $establishment= $this->EstablishmentRepository->getById($fields['establishment_id']);
            if ($establishment->owner_id != $user->id) {
                return ApiResponseClass::sendError('Only the owner of the establishment can add rule. ', null, 403);
            }
            $rule = $establishment->rules->create(['rule'=>$fields['rule']]);
             return ApiResponseClass::sendResponse($rule, 'Establishment rule saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error saving establishment rule: ' . $e->getMessage());
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
        ]);
        try {
            $user = auth('sanctum')->user();
            $establishment= $this->EstablishmentRepository->getById($fields['establishment_id']);
            if ($establishment->owner_id != $user->id) {
                return ApiResponseClass::sendError('Only the owner of the establishment can update rule.', null, 403);
            }
            $rule = $this->EstablishmentRuleRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($rule, 'Establishment rule updated successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error updating establishment rule: ' . $e->getMessage());
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
                return ApiResponseClass::sendError('Only the owner of the establishment can delete rule.', null, 403);
            }
            if($this->EstablishmentRuleRepository->delete($id)){
                return ApiResponseClass::sendResponse($rule, "{$rule->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("Rule with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting rule: ' . $e->getMessage());
        }
    }
}
