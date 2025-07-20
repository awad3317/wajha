<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bank;
use Livewire\WithFileUploads;

class Banks extends Component
{
    use WithFileUploads;

    public $banks;
    public $name, $icon, $bank_id;
    public $isEdit = false;
    public $deleteId = null;
    public $deleteName = null;
    public $search = '';

    public $iconFile;


    public function mount()
    {
        $this->loadBanks();
    }

    public function updatedSearch()
    {
        $this->loadBanks();
    }

    public function loadBanks()
    {
        $this->banks = Bank::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('id')->get();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:100|unique:banks,name',
            'iconFile' => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
        ]);

        $iconPath = null;
        if ($this->iconFile) {
            $iconPath = $this->iconFile->store('bank_icons', 'public');
        }

        Bank::create([
            'name' => $this->name,
            'icon' => $iconPath,
        ]);

        $this->resetForm();
        $this->loadBanks();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تمت إضافة البنك بنجاح'
        ]);
    }

    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        $this->bank_id = $bank->id;
        $this->name = $bank->name;
        $this->icon = $bank->icon;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:100|unique:banks,name,' . $this->bank_id,
            'iconFile' => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
        ]);

        $bank = Bank::findOrFail($this->bank_id);

        if ($this->iconFile) {
            $iconPath = $this->iconFile->store('bank_icons', 'public');
            $bank->icon = $iconPath;
        }

        $bank->name = $this->name;
        $bank->save();

        $this->resetForm();
        $this->loadBanks();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تمت تعديل البنك بنجاح'
        ]);
    }

    public function deleteBank()
    {
        Bank::findOrFail($this->deleteId)->delete();
        $this->loadBanks();
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
    }

    public function resetForm()
    {
        $this->name = '';
        $this->icon = null;
        $this->iconFile = null;
        $this->bank_id = null;
        $this->isEdit = false;
    }

    public function resetDelete()
    {
        $this->deleteId = null;
        $this->deleteName = null;
    }
    public function render()
    {
        return view('livewire.banks');
    }
}
