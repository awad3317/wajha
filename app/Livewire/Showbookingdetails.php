<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\BookingLog;

class Showbookingdetails extends Component
{
    public $booking;
    public $bookingLogs = [];

    public function mount($bookingId)
    {
        $this->booking = Booking::with(['user', 'establishment.owner', 'establishment.type'])
            ->findOrFail($bookingId);

        $this->bookingLogs = BookingLog::with('user')
            ->where('booking_id', $bookingId)
            ->orderBy('created_at', 'asc') // عشان يظهر التتبع بالترتيب
            ->get();
    }

    public function render()
    {
        return view('livewire.showbookingdetails', [
            'booking' => $this->booking,
            'bookingLogs' => $this->bookingLogs,
        ]);
    }
}