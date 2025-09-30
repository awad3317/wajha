<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Currency;
use App\Services\ImageService;
use App\Services\AdminLoggerService;

class Currencies extends Component
{
    use WithFileUploads;

    // public $currencies;
    public $name, $code, $symbol, $icon, $currency_id;
    public $isEdit = false;
    public $deleteId = null;
    public $deleteName = null;
    public $search = '';
    public $iconFile;
    public $showForm = false;

 

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:100|unique:currencies,name',
            'code' => 'required|string|max:10|unique:currencies,code',
            'symbol' => 'required|string|max:10',
            'iconFile' => 'required|mimes:jpg,jpeg,png,gif,svg,ico|max:2048',
        ]);

        $imageService = new ImageService();
        $iconPath = $imageService->saveImage($this->iconFile, 'currency_icons');

        Currency::create([
            'name' => $this->name,
            'code' => $this->code,
            'symbol' => $this->symbol,
            'icon' => $iconPath,
        ]);
        AdminLoggerService::log('اضافة عملة', 'Currency', "إضافة عملة جديد: {$this->name}");
        $this->resetForm();
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تمت إضافة العملة بنجاح'
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $currency = Currency::findOrFail($id);
        $this->currency_id = $currency->id;
        $this->name = $currency->name;
        $this->code = $currency->code;
        $this->symbol = $currency->symbol;
        $this->icon = $currency->icon;
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
            'name' => 'required|string|max:100|unique:currencies,name,' . $this->currency_id,
            'code' => 'required|string|max:10|unique:currencies,code,' . $this->currency_id,
            'symbol' => 'required|string|max:10',
            'iconFile' => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
        ]);

        $currency = Currency::findOrFail($this->currency_id);
        $imageService = new ImageService();

        if ($this->iconFile) {
            if ($currency->icon) {
                $imageService->deleteImage($currency->icon);
            }
            $currency->icon = $imageService->saveImage($this->iconFile, 'currency_icons');
        }

        $currency->name = $this->name;
        $currency->code = $this->code;
        $currency->symbol = $this->symbol;
        $currency->save();
        AdminLoggerService::log('تعديل عملة', 'Currency', "تعديل العملة: {$this->name}");
        $this->resetForm();
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم تعديل العملة بنجاح'
        ]);
    }

    public function deleteCurrency()
    {
        $currency = Currency::findOrFail($this->deleteId);
        $imageService = new ImageService();

        if ($currency->icon) {
            $imageService->deleteImage($currency->icon);
        }
        $currency->delete();
        AdminLoggerService::log('حذف عملة', 'Currency', "حذف العملة: {$currency->name}");
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم حذف العملة بنجاح'
        ]);
        $this->resetDelete();
    }

    public function confirmDelete($id)
    {
        $currency = Currency::findOrFail($id);
        AdminLoggerService::log('حذف عملة', 'Currency', "حذف العملة: {$currency->name}");
        $this->deleteId = $currency->id;
        $this->deleteName = $currency->name;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->code = '';
        $this->symbol = '';
        $this->icon = null;
        $this->iconFile = null;
        $this->currency_id = null;
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
         $currencies = Currency::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('id')->paginate(perPage: 10);
        return view('livewire.currencies', compact('currencies'));
    }
}