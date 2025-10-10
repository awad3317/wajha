<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\EstablishmentFeaturesIcon;
use App\Models\EstablishmentFeaturesIcons;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
{
  /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('auth');
    }

     public function index()
  {

    $currency = Currency::all();
    return view('currencies.index', compact('currency'));
  }
     public function establishment_features_icons()
  {

    $establishment_features_icons = EstablishmentFeaturesIcon::all();
    return view('establishment_features_icons.index', compact('establishment_features_icons'));
  }
}