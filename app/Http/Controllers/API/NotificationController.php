<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use Exception;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = auth('sanctum')->user();
            return ApiResponseClass::sendResponse([
                'unread' => $user->unreadNotifications,
                'read' => $user->readNotifications,
                'all' => $user->notifications,
                'stats' => [
                    'total' => $user->notifications->count(),
                    'unread' => $user->unreadNotifications->count(),
                    'read' => $user->readNotifications->count(),
                ]
            ],
                'All Notifications retrieved successfully. '
            );
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Notifications: ' . $e->getMessage());
        }
        
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
