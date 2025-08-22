<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking as BookingModel;

class Booking extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $stats = [
            'total' => BookingModel::count(),
            'pending' => BookingModel::where('status', 'pending')->count(),
            'paid' => BookingModel::where('status', 'paid')->count(),
            'confirmed' => BookingModel::where('status', 'confirmed')->count(),
            'completed' => BookingModel::where('status', 'completed')->count(),
            'cancelled' => BookingModel::where('status', 'cancelled')->count(),
        ];

        $bookings = BookingModel::with(['user', 'establishment', 'pricePackage', 'coupon'])
            ->when($this->search, fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%")))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy('booking_date', 'desc')
            ->paginate(10);

        return view('livewire.booking', compact('stats', 'bookings'));
    }
}