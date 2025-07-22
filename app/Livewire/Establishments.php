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

    public function getEstablishmentsProperty()
    {
        return Establishment::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->selectedType, fn($q) => $q->where('type_id', $this->selectedType))
            ->when($this->selectedStatus !== '', fn($q) => $q->where('is_verified', $this->selectedStatus))
            ->with(['type', 'region'])
            ->paginate(10);
    }
    public function toggleVerification($id)
    {
        $establishment = Establishment::find($id);
        if ($establishment) {
            $establishment->is_verified = !$establishment->is_verified;
            $establishment->save();

            $establishment->refresh();
        }
    }


    public function render()
    {
        $establishments = $this->getEstablishmentsProperty();
        $types = EstablishmentType::all();
        $statuses = [
            1 => 'موثقة',
            0 => 'غير موثقة',
        ];

        return view('livewire.establishments', compact('establishments', 'types', 'statuses'));
    }
}