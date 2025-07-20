<?php

namespace App\Livewire;

use App\Models\Advertisement;
use Livewire\Component;
use Livewire\WithFileUploads;

class Advertisements extends Component
{
    use WithFileUploads;

    public $advertisements;
    public $title, $description, $image, $imagePreview, $is_active = true, $start_date, $end_date, $advertisement_id;
    public $isEdit = false;
    public $deleteId = null;
    public $deleteTitle = null;
    public $search = '';

    public function mount()
    {
        $this->loadAdvertisements();
    }

    public function updatedSearch()
    {
        $this->loadAdvertisements();
    }

    public function loadAdvertisements()
    {
        $this->advertisements = Advertisement::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->get();
    }
    public function toggleActive($adId)
    {
        $ad = Advertisement::find($adId);
        if ($ad) {
            $ad->is_active = !$ad->is_active;
            $ad->save();

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => $ad->is_active ? 'تم تفعيل الإعلان بنجاح' : 'تم إلغاء تفعيل الإعلان بنجاح'
            ]);
        }
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => $this->isEdit ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'is_active' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
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

        $imageName = null;
        if ($this->image) {
            $imageName = $this->image->store('advertisements', 'public');
        }

        Advertisement::create([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $imageName,
            'is_active' => $this->is_active,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $this->resetForm();
        $this->loadAdvertisements();
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
    }

    public function update()
    {
        $this->validate();

        $advertisement = Advertisement::findOrFail($this->advertisement_id);

        $imageName = $advertisement->image;
        if ($this->image) {
            // حذف الصورة القديمة إذا تريد (اختياري)
            // Storage::disk('public')->delete($advertisement->image);

            $imageName = $this->image->store('advertisements', 'public');
        }

        $advertisement->update([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $imageName,
            'is_active' => $this->is_active,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $this->resetForm();
        $this->loadAdvertisements();
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
        // حذف الصورة من التخزين إذا تريد
        // Storage::disk('public')->delete($ad->image);
        $ad->delete();

        $this->loadAdvertisements();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم حذف الإعلان بنجاح',
        ]);

        $this->deleteId = null;
        $this->deleteTitle = null;
        $this->dispatch('close-delete-modal');
    }

    public function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->image = null;
        $this->imagePreview = null;
        $this->is_active = true;
        $this->start_date = '';
        $this->end_date = null;
        $this->advertisement_id = null;
        $this->isEdit = false;
    }
    public function render()
    {

        return view('livewire.advertisements');
    }
}
