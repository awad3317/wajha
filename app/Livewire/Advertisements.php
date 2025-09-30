<?php

namespace App\Livewire;

use App\Models\Advertisement;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\ImageService;
use App\Services\AdminLoggerService;
use Livewire\WithPagination;

class Advertisements extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap-5';
    
    // public $advertisements;
    public $title, $description, $image, $imagePreview, $is_active = true, $start_date, $end_date, $advertisement_id;
    public $isEdit = false;
    public $deleteId = null;
    public $deleteTitle = null;
    public $search = '';
    public $showForm = false;
    public $selectedStatu = null;


    protected $queryString = ['search', 'selectedStatu'];

    public function loadAdvertisements()
    {
       
    }
    public function cancel()
    {
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => $this->isEdit
                ? 'nullable|mimes:jpg,jpeg,png,gif,svg,ico|max:2048'
                : 'required|mimes:jpg,jpeg,png,gif,svg,ico|max:2048',
            'is_active' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'حقل العنوان مطلوب',
            'title.string' => 'يجب أن يكون العنوان نصاً',
            'title.max' => 'يجب ألا يتجاوز العنوان 255 حرفاً',
            'description.required' => 'حقل الوصف مطلوب',
            'image.required' => 'حقل الصورة مطلوب',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.max' => 'يجب ألا تتجاوز الصورة 2 ميجابايت',
            'start_date.required' => 'حقل تاريخ البدء مطلوب',
            'end_date.required' => 'حقل تاريخ الانتهاء مطلوب',
            'start_date.date' => 'يجب أن يكون تاريخ البدء صالحاً',
            'end_date.date' => 'يجب أن يكون تاريخ الانتهاء صالحاً',
            'end_date.after_or_equal' => 'يجب أن يكون تاريخ الانتهاء لاحقاً أو مساوياً لتاريخ البدء',
        ];
    }

    public function updatedImage()
    {
        $this->validateOnly('image');
    }

    public function store()
    {
        $this->validate();
        $imageService = new ImageService();
        $imageName = null;
        if ($this->image) {
            $imageName = $imageService->saveImage($this->image, 'advertisement-images');
        }

        Advertisement::create([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $imageName,
            'is_active' => $this->is_active,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
        AdminLoggerService::log('اضافة اعلان', 'Advertisement', "إضافة إعلان جديد: {$this->title}");
        $this->resetForm();
       
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم إضافة الإعلان بنجاح',
        ]);
    }

    public function edit($id)
    {
        $advertisement = Advertisement::findOrFail($id);
        $this->advertisement_id = $advertisement->id;
        $this->title = $advertisement->title;
        $this->description = $advertisement->description;
        $this->imagePreview = $advertisement->image;
        $this->is_active = $advertisement->is_active;
        $this->start_date = $advertisement->start_date->format('Y-m-d\TH:i');
        $this->end_date = $advertisement->end_date ? $advertisement->end_date->format('Y-m-d\TH:i') : null;
        $this->isEdit = true;
        // $this->showForm = true;
    }

    public function update()
    {
        $this->validate();

        $advertisement = Advertisement::findOrFail($this->advertisement_id);
        $imageService = new ImageService();

        $imageName = $advertisement->image;
        if ($this->image) {
            if ($advertisement->image) {
                $imageService->deleteImage($advertisement->image);
            }
            $imageName = $imageService->saveImage($this->image, 'advertisement-images');
        }

        $advertisement->update([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $imageName,
            'is_active' => $this->is_active,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
        AdminLoggerService::log('تعديل اعلان', 'Advertisement', "تعديل الإعلان: {$this->title}");
        $this->resetForm();
       
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم تعديل الإعلان بنجاح',
        ]);
    }

    public function confirmDelete($id)
    {
        $advertisement = Advertisement::findOrFail($id);
        $this->deleteId = $advertisement->id;
        $this->deleteTitle = $advertisement->title;
    }

    public function deleteAdvertisement()
    {
        $ad = Advertisement::findOrFail($this->deleteId);
        $imageService = new ImageService();
        if ($ad->image) {
            $imageService->deleteImage($ad->image);
        }
        $ad->delete();
        AdminLoggerService::log('حذف اعلان', 'Advertisement', "حذف الإعلان: {$this->deleteTitle}");
       
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم حذف الإعلان بنجاح',
        ]);

        $this->deleteId = null;
        $this->deleteTitle = null;
        $this->dispatch('close-delete-modal');
    }
    public function toggleVerification($id)
    {
        $ad = Advertisement::findOrFail($id);
        $ad->update([
            'is_active' => !$ad->is_active
        ]);
        AdminLoggerService::log('تعديل حالة اعلان', 'Advertisement', "تعديل حالة الإعلان: {$this->deleteTitle}");

       

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => $ad->is_active ? 'تم تفعيل الإعلان بنجاح' : 'تم إلغاء تفعيل الإعلان بنجاح'
        ]);
    }


    public function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->image = null;
        $this->imagePreview = null;
        $this->is_active = true;
        $this->start_date = '';
        $this->end_date = '';
        $this->advertisement_id = null;
        $this->isEdit = false;
        $this->showForm = false;
    }
    public function render()
    {
         $advertisements = Advertisement::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when(is_numeric($this->selectedStatu), function ($query) {
                $query->where('is_active', $this->selectedStatu);
            })
            ->orderBy('id', 'desc')
            ->paginate(perPage: 10);

        return view('livewire.advertisements',compact('advertisements'));
    }
}