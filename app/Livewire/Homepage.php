<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Establishment;
use App\Models\booking;
use App\Models\DiscountCoupon;
use App\Models\Advertisement;
use App\Models\EstablishmentType;
use App\Models\Review;

class Homepage extends Component
{
    public $usersCount, $ownersCount, $adminsCount;
    public $establishmentsCount, $bookingsCount;
    public $activeCoupons, $expiredCoupons;
    public $activeAds;

    public function mount()
    {
        $this->usersCount = User::where('user_type', 'user')->count();
        $this->ownersCount = User::where('user_type', 'owner')->count();
        $this->adminsCount = User::where('user_type', 'admin')->count();

        $this->establishmentsCount = Establishment::count();
        $this->bookingsCount = booking::count();

        $this->activeCoupons = DiscountCoupon::where('is_active', 1)->count();
        $this->expiredCoupons = DiscountCoupon::where('end_date', '<', now())->count();

        $this->activeAds = Advertisement::where('is_active', 1)->count();
    }

    public function render()
    {
        $latestBookings = booking::with('user', 'establishment')->latest()->take(5)->get();
        $latestEstablishments = Establishment::latest()->take(5)->get();
        $latestAds = Advertisement::latest()->take(5)->get();
        $latestReviews = Review::with('user', 'establishment')->latest()->take(5)->get();

        // Chart Data
        $bookingsChart = booking::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(10))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $typesChart = EstablishmentType::selectRaw('name, COUNT(*) as total')
            ->groupBy('name')
            ->orderBy('total', 'desc')
            ->get();

        $usersChart = User::selectRaw('user_type, COUNT(*) as total')
            ->groupBy('user_type')
            ->get();

        return view('livewire.homepage', compact(
            'latestBookings',
            'latestEstablishments',
            'latestAds',
            'latestReviews',
            'bookingsChart',
            'typesChart',
            'usersChart'
        ));
    }
}