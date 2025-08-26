<?php

namespace App\Http\Controllers;

use App\Models\booking;
use App\Models\BookingLog;
use Illuminate\Http\Request;

class BookingController extends Controller
{
     public function index() {

        $booking = booking::latest()->paginate(5);
        return view('booking.index',compact('booking'));
        
    }
    public function show($id) {
        $booking = booking::find($id);
        return view('booking.show',compact('booking'));
    }
}