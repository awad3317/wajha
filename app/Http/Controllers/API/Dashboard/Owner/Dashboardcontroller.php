<?php

namespace App\Http\Controllers\API\Dashboard\Owner;

use Exception;
use Illuminate\Http\Request;
use App\Models\Establishment;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class Dashboardcontroller extends Controller
{
    public function __construct()
    {
    
    }

    public function index()
    {
        $ownerId = auth('sanctum')->user()->id; 

        $stats = DB::table('establishments')
        ->leftJoin('reviews', 'establishments.id', '=', 'reviews.establishment_id')
        ->where('establishments.owner_id', $ownerId)
        ->select(
        DB::raw('COUNT(DISTINCT establishments.id) as establishments_count'),
        DB::raw('AVG(reviews.rating) as average_rating'),
        DB::raw('COUNT(reviews.id) as total_reviews'))->first();

        $totalBookings = DB::table('bookings')
        ->join('establishments', 'bookings.establishment_id', '=', 'establishments.id')
        ->where('establishments.owner_id', $ownerId)
        ->count();

        $myEstablishments = Establishment::where('owner_id', $ownerId)->with(['type', 'region.parent'])->get();
        return ApiResponseClass::sendResponse([
            'my_stablishments_count' => $stats->establishments_count,
            'average_rating' => $stats->average_rating,
            'bookings_count' => $totalBookings,
            'my_stablishments' => $myEstablishments
        ], 'Dashboard data retrieved successfully.');
    }
}
