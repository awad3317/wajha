<?php

namespace App\Http\Controllers;

use App\Models\booking;
use App\Models\BookingLog;
use Illuminate\Http\Request;

class BookingController extends Controller
{
     public function index() {

        $booking = Booking::latest()->paginate(5);
        return view('booking.index',compact('booking'));
        
    }
    public function show($id) {
        $booking = Booking::find($id);
        return view('booking.show',compact('booking'));
    }
}