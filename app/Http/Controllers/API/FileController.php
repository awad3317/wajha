<?php

namespace App\Http\Controllers\api;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function downloadReceipt($bookingId)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json(['error' => 'غير مصرح بالوصول'], 403);
            }
            
            // البحث باستخدام معرّف الحجز
            $booking = Booking::with('establishment')->find($bookingId);
            
            if (!$booking) {
                return response()->json(['error' => 'الحجز غير موجود'], 404);
            }
            
            $filePath = $booking->payment_receipt_image;
            
            if (!$filePath) {
                return response()->json(['error' => 'لا يوجد إيصال دفع لهذا الحجز'], 404);
            }
            
            // التحقق من صلاحية المستخدم
            if ($user->user_type === 'admin') {
                return $this->downloadFile($filePath);
            }
           
            $isEstablishmentOwner = $booking->establishment->owner_id === $user->id;
            $isBooker = $booking->user_id === $user->id;
            
            if (!$isEstablishmentOwner && !$isBooker) {
                return response()->json(['error' => 'غير مصرح بالوصول'], 403);
            }
            
            return $this->downloadFile($filePath);
            
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
        
            // البحث باستخدام معرّف الحجز
            $booking = Booking::with('establishment')->find($bookingId);
            
            if (!$booking) {
                return response()->json(['error' => 'الحجز غير موجود'], 404);
            }
            
            $filePath = $booking->payment_receipt_image;
            
            if (!$filePath) {
                return response()->json(['error' => 'لا يوجد إيصال دفع لهذا الحجز'], 404);
            }
            
            // التحقق من صلاحية المستخدم
            if ($user->user_type === 'admin') {
                return $this->viewFile($filePath);
            }
            
            $isEstablishmentOwner = $booking->establishment->owner_id === $user->id;
            $isBooker = $booking->user_id === $user->id;
        
            if (!$isEstablishmentOwner && !$isBooker) {
                return response()->json(['error' => 'غير مصرح بالوصول'], 403);
            }
            
            return $this->viewFile($filePath);
                
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء عرض الملف',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحميل الملف من التخزين الخاص
     */
    private function downloadFile($filePath)
    {
        if (!Storage::disk('private')->exists($filePath)) {
            return response()->json(['error' => 'الملف غير موجود'], 404);
        }
        
        $filename = basename($filePath);
        
        return Storage::disk('private')->download($filePath, $filename);
    }

    /**
     * عرض الملف مباشرة في المتصفح
     */
    private function viewFile($filePath)
    {
        if (!Storage::disk('private')->exists($filePath)) {
            return response()->json(['error' => 'الملف غير موجود'], 404);
        }
        
        $file = Storage::disk('private')->get($filePath);
        $mimeType = Storage::disk('private')->mimeType($filePath);
        $filename = basename($filePath);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}