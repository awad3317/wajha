<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;
class Bookinges extends Component
{
   use WithPagination;

    public $search = '';
    public $statusFilter = '';

    protected $paginationTheme = 'bootstrap';
    public $showBookingDetails = false;
    
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'paid' => Booking::where('status', 'paid')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        $bookings = Booking::with(['user', 'establishment', 'pricePackage', 'coupon'])
            ->when($this->search, fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%")))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy('booking_date', 'desc')
            ->paginate(10);

        return view('livewire.bookinges', compact('stats', 'bookings'));
    }
}