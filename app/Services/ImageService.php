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
        // 🔧 المسار الصحيح بدون تكرار
        $basePath = base_path(); // هذا يعطي: /home/tiyar-wejha/htdocs/wajha/wajha/
        $correctPath = $basePath . '/public/storage/' . str_replace('storage/', '', $imagePath);
        
        // بديل آخر: استخدام public_path مباشرة
        $alternativePath = public_path('storage/' . str_replace('storage/', '', $imagePath));

        Log::info('🔍 محاولة الحذف من المسار المصحح', [
            'image_path' => $imagePath,
            'base_path' => $basePath,
            'correct_path' => $correctPath,
            'alternative_path' => $alternativePath,
            'correct_path_exists' => file_exists($correctPath),
            'alternative_path_exists' => file_exists($alternativePath)
        ]);

        // المحاولة الأولى: المسار المصحح
        if (file_exists($correctPath)) {
            if (unlink($correctPath)) {
                Log::info('✅ تم حذف الملف بنجاح من المسار المصحح', ['path' => $correctPath]);
                return true;
            }
        }

        // المحاولة الثانية: المسار البديل
        if (file_exists($alternativePath)) {
            if (unlink($alternativePath)) {
                Log::info('✅ تم حذف الملف بنجاح من المسار البديل', ['path' => $alternativePath]);
                return true;
            }
        }

        // المحاولة الثالثة: البحث في المجلد مباشرة
        $filename = basename($imagePath);
        $directSearchPath = $basePath . '/public/storage/bank_icons/' . $filename;
        
        if (file_exists($directSearchPath)) {
            if (unlink($directSearchPath)) {
                Log::info('✅ تم الحذف بعد البحث المباشر', ['path' => $directSearchPath]);
                return true;
            }
        }

        Log::error('❌ تعذر العثور على الملف', [
            'searched_paths' => [
                $correctPath,
                $alternativePath,
                $directSearchPath
            ],
            'directory_contents' => file_exists($basePath . '/public/storage/bank_icons/') ? 
                scandir($basePath . '/public/storage/bank_icons/') : 'Directory not found'
        ]);

        return false;

    } catch (\Exception $e) {
        Log::error('💥 خطأ أثناء محاولة الحذف', [
            'image_path' => $imagePath,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return false;
    }
}

}