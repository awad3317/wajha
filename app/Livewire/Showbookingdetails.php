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
        $this->booking = Booking::with('user')->findOrFail($bookingId);
        $this->bookingLogs = BookingLog::with('user')
            ->where('booking_id', $bookingId)
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function render()
    {
        return view('livewire.showbookingdetails');
    }
}