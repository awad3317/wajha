<?php

namespace App\Livewire;

use App\Models\Establishment;
use App\Models\EstablishmentType;
use Livewire\Component;
use Livewire\WithPagination;

class Establishments extends Component
{
   use WithPagination;

    public $search = '';
    public $selectedType = '';
    public $selectedStatus = '';

    protected $paginationTheme = 'bootstrap';

        protected $queryString = ['search', 'selectedType', 'selectedStatus'];

    public function updating($field)
    {
        $this->resetPage();
    }

    public function toggleVerification($id)
    {
        $establishment = Establishment::find($id);
        if ($establishment) {
            $establishment->is_verified = !$establishment->is_verified;
            $establishment->save();
        }
    }

    public function render()
    {
        $establishments = Establishment::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->selectedType, fn($q) => $q->where('type_id', $this->selectedType))
            ->when($this->selectedStatus !== '', fn($q) => $q->where('is_verified', $this->selectedStatus))
            ->with(['type', 'region'])
            ->latest()
            ->paginate(10);

        $types = EstablishmentType::all();
        $statuses = [
            1 => 'موثقة',
            0 => 'غير موثقة',
        ];

        return view('livewire.establishments', compact('establishments', 'types', 'statuses'));
    }
}