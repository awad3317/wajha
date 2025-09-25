<?php


namespace App\Services;

use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Save the image and return its path.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $folder
     * @return string
     */
    public function saveImage($image, $folder)
    {
        $filename = uniqid('', true) . '.' . $image->getClientOriginalExtension();
        $filePath = $folder . '/' . $filename;

        $image->storeAs($folder, $filename, 'public');
       
        return $filePath;
    }


public function deleteImage(?string $imagePath): bool
{
    if (empty($imagePath)) {
        Log::warning('محاولة حذف صورة بمسار فارغ', [
            'image_path' => $imagePath,
            'timestamp' => now()->toDateTimeString(),
            'method' => __METHOD__
        ]);
        return false;
    }

    $storagePath = $imagePath;
    
    if (!Storage::disk('public')->exists($storagePath)) {
        Log::error('الملف غير موجود للحذف', [
            'image_path' => $imagePath,
            'storage_path' => $storagePath,
            'timestamp' => now()->toDateTimeString(),
            'method' => __METHOD__
        ]);
        return false;
    }

    try {
        $deleted = Storage::disk('public')->delete($storagePath);
        
        if ($deleted) {
            Log::info('تم حذف الصورة بنجاح', [
                'image_path' => $imagePath,
                'storage_path' => $storagePath,
                'timestamp' => now()->toDateTimeString(),
                'method' => __METHOD__
            ]);
        } else {
            Log::error('فشل في حذف الصورة (الدالة أعادت false)', [
                'image_path' => $imagePath,
                'storage_path' => $storagePath,
                'timestamp' => now()->toDateTimeString(),
                'method' => __METHOD__
            ]);
        }
        
        return $deleted;
        
    } catch (\Exception $e) {
        Log::error('حدث خطأ أثناء حذف الصورة', [
            'image_path' => $imagePath,
            'storage_path' => $storagePath,
            'error_message' => $e->getMessage(),
            'error_file' => $e->getFile(),
            'error_line' => $e->getLine(),
            'timestamp' => now()->toDateTimeString(),
            'method' => __METHOD__
        ]);
        return false;
    }
}

}