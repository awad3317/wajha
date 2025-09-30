<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bank;
use Livewire\WithFileUploads;
use App\Services\ImageService;
use App\Services\AdminLoggerService;
use Livewire\WithPagination;

class Banks extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'pagination::bootstrap-5';
    // public $banks;
    public $name, $icon, $bank_id;
    public $isEdit = false;
    public $deleteId = null;
    public $deleteName = null;
    public $search = '';
    public $iconFile;
    public $showForm = false;



    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:100|unique:banks,name',
            'iconFile' => 'required|image|mimes:jpg,jpeg,png,gif,svg,ico|max:2048',

        ]);
        $imageService = new ImageService();
        $iconPath = null;
        if ($this->iconFile) {
            $iconPath = $imageService->saveImage($this->iconFile, 'bank_icons');
        }



        Bank::create([
            'name' => $this->name,
            'icon' => $iconPath,
        ]);
        AdminLoggerService::log('اضافة بنك', 'Bank', "إضافة بنك جديد: {$this->name}");

        $this->resetForm();
       
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تمت إضافة البنك بنجاح'
        ]);
    }
    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        $this->bank_id = $bank->id;
        $this->name = $bank->name;
        $this->icon = $bank->icon;
        $this->isEdit = true;
        $this->showForm = true;
    }
    public function cancel()
    {
        $this->resetForm();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:100|unique:banks,name,' . $this->bank_id,
            'iconFile' => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
        ]);
        $imageService = new ImageService();
        $bank = Bank::findOrFail($this->bank_id);

        if ($this->iconFile) {
            if ($bank->icon) {
                $imageService->deleteImage($bank->icon);
            }

            $bank->icon = $imageService->saveImage($this->iconFile, 'bank_icons');
        }

        $bank->name = $this->name;
        $bank->save();
        AdminLoggerService::log('تعديل بنك', 'Bank', "تعديل البنك: {$this->name}");
        $this->resetForm();
       
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تمت تعديل البنك بنجاح'
        ]);
    }

    public function deleteBank()
    {
        $imageService = new ImageService();

        $bank = Bank::findOrFail($this->deleteId);
        if ($bank->icon) {
            $imageService->deleteImage($bank->icon);
        }
        $bank->delete();
        AdminLoggerService::log('حذف بنك', 'Bank', "حذف البنك: {$bank->name}");
       
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تمت حذف البنك بنجاح'
        ]);
        $this->resetDelete();
    }
    public function confirmDelete($id)
    {
        $bank = Bank::findOrFail($id);
        $this->deleteId = $bank->id;
        $this->deleteName = $bank->name;
        AdminLoggerService::log('حذف بنك', 'Bank', "حذف البنك: {$bank->name}");
    }

    public function resetForm()
    {
        $this->name = '';
        $this->icon = null;
        $this->iconFile = null;
        $this->bank_id = null;
        $this->isEdit = false;
        $this->showForm = false;
    }

    public function resetDelete()
    {
        $this->deleteId = null;
        $this->deleteName = null;
    }
    public function render()
    {
         $banks = Bank::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('id')->paginate(perPage: 2);
        return view('livewire.banks', compact('banks'));
    }
}