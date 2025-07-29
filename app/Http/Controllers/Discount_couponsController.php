<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Discount_couponsController extends Controller
{
    public function index()
    {
        return view('discount_coupons.index');
    }
}