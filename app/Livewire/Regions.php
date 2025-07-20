<?php

namespace App\Livewire;

use App\Models\Region;
use Livewire\Component;

class Regions extends Component
{

    public $regions;
    public $name, $parent_id, $region_id;
    public $isEdit = false;
    public $deleteId = null;
    public $deleteName = null;
    public $search = '';

    public function mount()
    {
        $this->loadRegions();
    }
    public function updatedSearch()
    {
        $this->loadRegions();
    }
    public function loadRegions()
    {
        $this->regions = Region::with('parent')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'asc')
            ->get();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        Region::create([
            'name' => $this->name,
            'parent_id' => $this->parent_id,
        ]);

        $this->resetForm();
        $this->loadRegions();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم اضافة المنطقه بنجاح'
        ]);
    }

    public function edit($id)
    {

        $region = Region::findOrFail($id);
        $this->region_id = $region->id;
        $this->name = $region->name;
        $this->parent_id = $region->parent_id;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $region = Region::findOrFail($this->region_id);
        $region->update([
            'name' => $this->name,
            'parent_id' => $this->parent_id,
        ]);

        $this->resetForm();
        $this->loadRegions();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم تعديل المنطقه بنجاح'
        ]);
    }
    public function confirmDelete($id)
    {
        $region = Region::findOrFail($id);
        $this->deleteId = $region->id;
        $this->deleteName = $region->name;
    }
    public function deleteRegion()
    {
        Region::findOrFail($this->deleteId)->delete();
        $this->loadRegions();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم حذف المنطقه بنجاح'
        ]);
        $this->deleteId = null;
        $this->deleteName = null;

        $this->dispatch('close-delete-modal');
    }


    public function resetForm()
    {
        $this->name = '';
        $this->parent_id = null;
        $this->region_id = null;
        $this->isEdit = false;
    }

    public function render()
    {
        $parents = Region::all();
        return view('livewire.regions', compact('parents'));
    }
}