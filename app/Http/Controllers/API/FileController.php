<?php

namespace App\Http\Controllers\api;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function downloadReceipt($bookingId)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json(['error' => 'غير مصرح بالوصول'], 403);
            }
            
            $booking = Booking::find($bookingId);
            
            if (!$booking) {
                return response()->json(['error' => 'الحجز غير موجود'], 404);
            }
            
            $filePath = $booking->payment_receipt_image;
            
            if (!$filePath) {
                return response()->json(['error' => 'لا يوجد إيصال دفع لهذا الحجز'], 404);
            }
            
            if ($user->user_type === 'admin') {
                return Storage::disk('private')->download($filePath);
            }
           
            $isEstablishmentOwner = $booking->establishment->owner_id === $user->id;
            
            $isBooker = $booking->user_id === $user->id;
            
            if (!$isEstablishmentOwner && !$isBooker) {
                return response()->json(['error' => 'غير مصرح بالوصول'], 403);
            }
            
            if (!Storage::disk('private')->exists($filePath)) {
                return response()->json(['error' => 'الملف غير موجود'], 404);
            }
            
            $filename = basename($filePath);
            
            return Storage::disk('private')->download($filePath, $filename);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء تحميل الملف',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function viewReceipt($bookingId)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json(['error' => 'غير مصرح بالوصول'], 403);
            }
        
            $booking = Booking::find($bookingId);
            
            if (!$booking) {
                return response()->json(['error' => 'الحجز غير موجود'], 404);
            }
            
            $filePath = $booking->payment_receipt_image;
            
            if (!$filePath) {
                return response()->json(['error' => 'لا يوجد إيصال دفع لهذا الحجز'], 404);
            }
            
            if (!Storage::disk('private')->exists($filePath)) {
                return response()->json(['error' => 'الملف غير موجود'], 404);
            }
            
            if ($user->user_type === 'admin') {
                $file = Storage::disk('private')->get($filePath);
                $mimeType = Storage::disk('private')->mimeType($filePath);
                $filename = basename($filePath);
                
                return response($file, 200)
                    ->header('Content-Type', $mimeType)
                    ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
            }
            
            $isEstablishmentOwner = $booking->establishment->owner_id === $user->id;
            
            $isBooker = $booking->user_id === $user->id;
        
            if (!$isEstablishmentOwner && !$isBooker) {
                return response()->json(['error' => 'غير مصرح بالوصول'], 403);
            }
            
            $file = Storage::disk('private')->get($filePath);
            $mimeType = Storage::disk('private')->mimeType($filePath);
            $filename = basename($filePath);
            
            return response($file, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
                
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء عرض الملف',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}