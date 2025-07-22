<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Establishment;

class ShowInfo extends Component
{
    public $establishment;

    public function mount($id)
    {
        $this->establishment = Establishment::with([
            'owner',
            'type',
            'region',
            'images',
            'features',
            'rules',
            'specifications',
            'unavailabilities'
        ])->findOrFail($id);
    }
    public function render()
    {
        return view('livewire.show-info', [
            'images' => $this->establishment->images,
            'features' => $this->establishment->features,
            'rules' => $this->establishment->rules,
            'specifications' => $this->establishment->specifications,
            'unavailabilities' => $this->establishment->unavailabilities,
        ]);
    }
}