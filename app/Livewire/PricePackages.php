<?php

namespace App\Livewire;

use App\Models\PricePackage;
use App\Models\PricePackageIcon;
use App\Models\Establishment;
use App\Models\Currency;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\AdminLoggerService;

class PricePackages extends Component
{
    use WithPagination;

    public $name, $description, $price;
    public $featuresInput;  
    public $features = [];  
    public $is_active = true;
    public $establishment_id, $icon_id;
    public $search = '';
    public $pricePackageId;
    public $isEdit = false;
    public $showForm = false;
    public $deleteId = null;
    public $deleteName = null;
    protected $paginationTheme = 'bootstrap';
    public $currency_id;


    protected $rules = [
        'name' => 'required|string|max:100',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'features' => 'nullable|array',
        'features.*' => 'string',
        'is_active' => 'boolean',
        'icon_id' => 'nullable|exists:price_package_icons,id',
        'establishment_id' => 'required|exists:establishments,id',
        'currency_id' => 'required|exists:currencies,id',

    ];

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->features = array_filter(array_map('trim', explode(',', $this->featuresInput)));
        $this->validate();

        PricePackage::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'features' => $this->features,
            'is_active' => $this->is_active,
            'icon_id' => $this->icon_id,
            'establishment_id' => $this->establishment_id,
            'currency_id' => $this->currency_id,
        ]);
        AdminLoggerService::log('اضافة باقة', 'PricePackage', "إضافة باقة جديدة: {$this->name}");
        $this->resetForm();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم إضافة الباقة بنجاح',
            'title' => 'نجاح',
            'icon' => 'success',
        ]);
    }

    public function edit($id)
    {
        $package = PricePackage::findOrFail($id);
        $this->pricePackageId = $id;
        $this->name = $package->name;
        $this->description = $package->description;
        $this->price = $package->price;
        $this->features = $package->features ?? [];
        $this->featuresInput = implode(', ', $this->features);
        $this->is_active = $package->is_active;
        $this->icon_id = $package->icon_id;
        $this->establishment_id = $package->establishment_id;
        $this->currency_id = $package->currency_id;
        $this->isEdit = true;
        $this->showForm = true;
    }

    public function update()
    {
        $this->features = array_filter(array_map('trim', explode(',', $this->featuresInput)));
        $this->validate();

        $package = PricePackage::findOrFail($this->pricePackageId);
        $package->update([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'features' => $this->features,
            'is_active' => $this->is_active,
            'icon_id' => $this->icon_id,
            'establishment_id' => $this->establishment_id,
            'currency_id' => $this->currency_id,
        ]);
        AdminLoggerService::log('تعديل باقة', 'PricePackage', "تعديل باقة جديدة: {$this->name}");
        $this->resetForm();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم تعديل الباقة بنجاح'
        ]);
    }

    public function delete($id)
    {
        $package = PricePackage::findOrFail($id);
        $package->delete();
        $this->resetDelete();
    }
 public function deletePackage()
{
    $package = PricePackage::findOrFail($this->deleteId);
    $name = $package->name; 

    $package->delete();

    AdminLoggerService::log('delete', 'PricePackage', "تم حذف الباقة: {$name}");

    $this->dispatch('show-toast', [
        'type' => 'success',
        'message' => "تم حذف الباقة ({$name}) بنجاح",
        'title' => 'نجاح',
        'icon' => 'success',
    ]);

    $this->resetDelete();
}

    public function resetForm()
    {
        $this->reset([
            'name',
            'description',
            'price',
            'features',
            'featuresInput',
            'is_active',
            'icon_id',
            'establishment_id',
            'showForm',
            'isEdit',
            'pricePackageId',
            'currency_id'
        ]);
    }
    public function confirmDelete($id)
    {
        $Package = PricePackage::findOrFail($id);
        $this->deleteId = $Package->id;
        $this->deleteName = $Package->name;
    }
    public function resetDelete()
    {
        $this->deleteId = null;
        $this->deleteName = null;
    }
    public function render()
    {
        $packages = PricePackage::latest()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->paginate(10);

        return view('livewire.price-packages', [
            'packages' => $packages,
            'icons' => PricePackageIcon::all(),
            'establishments' => Establishment::all(),
            'currencies' => Currency::all(),
        ]);
    }
}