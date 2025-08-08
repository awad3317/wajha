<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\establishmentFeaturesIcon;
use App\Models\EstablishmentFeaturesIcons;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
{
     public function index()
  {

    $currency = Currency::all();
    return view('currencies.index', compact('currency'));
  }
     public function establishment_features_icons()
  {

    $establishment_features_icons = establishmentFeaturesIcon::all();
    return view('establishment_features_icons.index', compact('establishment_features_icons'));
  }
}