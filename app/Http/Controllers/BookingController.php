<?php

namespace App\Http\Controllers;

use App\Models\booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
     public function index() {

        $booking = booking::latest()->paginate(5);
        return view('booking.index',compact('booking'));
        
    }
}