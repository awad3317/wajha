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
        Log::warning('محاولة حذف صورة بمسار فارغ', ['image_path' => $imagePath]);
        return false;
    }

    try {
        // 🔧 المسار المطلق للملف بناءً على موقعك الفعلي
        $absolutePath = base_path('htdocs/wajha/wajha/public/storage/' . str_replace('storage/', '', $imagePath));
        
        Log::info('🔍 محاولة الحذف من المسار', [
            'image_path' => $imagePath,
            'absolute_path' => $absolutePath,
            'file_exists' => file_exists($absolutePath)
        ]);

        // الحل 1: الحذف المباشر باستخدام PHP
        if (file_exists($absolutePath)) {
            if (unlink($absolutePath)) {
                Log::info('✅ تم حذف الملف بنجاح', [
                    'path' => $absolutePath,
                    'method' => 'direct_unlink'
                ]);
                return true;
            } else {
                Log::error('❌ فشل في حذف الملف مباشرة', ['path' => $absolutePath]);
            }
        }

        // الحل 2: استخدام Storage مع disk مخصص
        $storagePath = str_replace('storage/', '', $imagePath);
        
        // إنشاء disk مخصص للمسار الصحيح
        if (Storage::disk('custom_public')->exists($storagePath)) {
            if (Storage::disk('custom_public')->delete($storagePath)) {
                Log::info('✅ تم الحذف عبر disk مخصص', ['path' => $storagePath]);
                return true;
            }
        }

        Log::error('❌ تعذر العثور على الملف أو حذفه', [
            'searched_path' => $absolutePath,
            'image_path' => $imagePath,
            'storage_path' => $storagePath
        ]);

        return false;

    } catch (\Exception $e) {
        Log::error('💥 خطأ أثناء محاولة الحذف', [
            'image_path' => $imagePath,
            'error' => $e->getMessage()
        ]);
        return false;
    }
}

}