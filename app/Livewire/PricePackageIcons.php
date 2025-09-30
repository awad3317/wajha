<?php

namespace App\Livewire;

use App\Models\pricePackageIcon;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\ImageService;
use App\Services\AdminLoggerService;
use Livewire\WithPagination;

class PricePackageIcons extends Component
{
    use WithFileUploads;
    use WithPagination;
    public  $icon, $package_id;
    public $isEdit = false;
    public $deleteId = null;
    public $search = '';
    public $iconFile;
    public $showForm = false;



    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function store()
    {
        $this->validate([
            'iconFile' => 'required|image|mimes:jpg,jpeg,png,svg|max:2048',
        ], [
            'iconFile.required' => 'يرجى اختيار أيقونة.',
            'iconFile.image' => 'الملف يجب أن يكون صورة.',
            'iconFile.mimes' => 'الملف يجب أن يكون من نوع: jpg, jpeg, png, svg.',
            'iconFile.max' => 'الحد الأقصى لحجم الصورة 2MB.',
        ]);;

        $imageService = new ImageService();
        $iconPath = $this->iconFile ? $imageService->saveImage($this->iconFile, 'package_icons') : null;

        pricePackageIcon::create([
            'icon' => $iconPath,
        ]);
        AdminLoggerService::log('اضافة ايقونه', 'PricePackageIcon', "إضافة الأيقونة: {$this->icon}");
        $this->resetForm();
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تمت إضافة الأيقونة بنجاح'
        ]);
    }
    public function cancel()
    {
        $this->resetForm();
    }

    public function edit($id)
    {
        $package = pricePackageIcon::findOrFail($id);
        $this->package_id = $package->id;
        $this->icon = $package->icon;
        $this->isEdit = true;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate([
            'iconFile' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        $imageService = new ImageService();
        $package = pricePackageIcon::findOrFail($this->package_id);

        if ($this->iconFile) {
            if ($package->icon) {
                $imageService->deleteImage($package->icon);
            }
            $package->icon = $imageService->saveImage($this->iconFile, 'package_icons');
        }

        $package->save();
        AdminLoggerService::log('تعديل ايقونه', 'PricePackageIcon', "تعديل الأيقونة: {$this->icon}");
        $this->resetForm();
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم تعديل الأيقونة بنجاح'
        ]);
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function deletepackage()
    {
        $package = pricePackageIcon::findOrFail($this->deleteId);
        if ($package->icon) {
            $imageService = new ImageService();
            $imageService->deleteImage($package->icon);
        }
        $package->delete();
        AdminLoggerService::log('حذف ايقونه', 'PricePackageIcon', "حذف الأيقونة: {$this->icon}");
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم حذف الأيقونة بنجاح'
        ]);
        $this->resetDelete();
    }

    public function resetForm()
    {
        $this->icon = null;
        $this->iconFile = null;
        $this->package_id = null;
        $this->isEdit = false;
        $this->showForm = false;
    }

    public function resetDelete()
    {
        $this->deleteId = null;
    }

    public function render()
    {
        $packages = pricePackageIcon::query()->paginate(perPage: 10);
        return view('livewire.price-package-icons', compact('packages'));
    }
}