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
        $this->booking = Booking::with(['user', 'establishment.owner', 'establishment.type', 'couponUse'])
            ->findOrFail($bookingId);

        $this->bookingLogs = BookingLog::with('user')
            ->where('booking_id', $bookingId)
            ->orderBy('created_at', 'asc') 
            ->get();
    }
    private function calculatePrice()
    {
        $originalPrice = $this->booking->pricePackage->price ?? 0;

        $discountType = $this->booking->couponUse->coupon->discount_type ?? null;
        $discountValue = $this->booking->couponUse->coupon->discount_value ?? 0;

        $finalPrice = $originalPrice;
        $discountLabel = 'بدون خصم';

        if ($discountType === 'percentage' && $discountValue > 0) {
            $finalPrice = $originalPrice - ($originalPrice * ($discountValue / 100));
            $discountLabel = $discountValue . ' %';
        } elseif ($discountType === 'fixed_amount' && $discountValue > 0) {
            $finalPrice = $originalPrice - $discountValue;
            $discountLabel = number_format($discountValue, 2) . ' ' . ($this->booking->pricePackage->currency->symbol ?? '');
        }

        return [$finalPrice, $discountLabel, $originalPrice, $discountType];
    }



    public function render()
    {
        [$finalPrice, $discountLabel, $originalPrice, $discountType] = $this->calculatePrice();

        return view('livewire.showbookingdetails', [
            'booking' => $this->booking,
            'bookingLogs' => $this->bookingLogs,
            'finalPrice' => $finalPrice,
            'discountLabel' => $discountLabel,
            'originalPrice' => $originalPrice,
            'discountType' => $discountType,
        ]);
    }
}