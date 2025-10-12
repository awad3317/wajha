<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Establishment;
use App\Models\Booking;
use App\Models\DiscountCoupon;
use App\Models\Advertisement;
use App\Models\EstablishmentType;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

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
        $this->bookingsCount = Booking::count();

        $this->activeCoupons = DiscountCoupon::where('is_active', 1)->count();
        $this->expiredCoupons = DiscountCoupon::where('end_date', '<', now())->count();

        $this->activeAds = Advertisement::where('is_active', 1)->count();
    }

    public function render()
    {
        $latestBookings = Booking::with('establishment')
            ->select(
                'establishment_id',
                DB::raw('COUNT(*) as total'),
                DB::raw('MAX(booking_date) as last_booking_date')
            )
            ->groupBy('establishment_id')
            ->orderByDesc('total')
            ->get();


        $latestEstablishments = Establishment::latest()->take(5)->get();
        $latestAds = Advertisement::latest()->take(5)->get();
        $latestReviews = Review::with('user', 'establishment')->latest()->take(5)->get();

        // Chart Data
        $bookingsChart = Booking::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(10))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $typesChart = DB::table('establishments')
            ->join('establishment_types', 'establishments.type_id', '=', 'establishment_types.id')
            ->select('establishment_types.name', DB::raw('COUNT(establishments.id) as total'))
            ->groupBy('establishment_types.name')
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
