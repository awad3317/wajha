<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
{
     public function index()
  {

    $currency = Currency::all();
    return view('currencies.index', compact('currency'));
  }
}