<?php

namespace App\Http\Controllers;

use App\Models\bank;
use App\Models\PricePackage;
use App\Models\pricePackageIcon;
use Illuminate\Http\Request;

class BanksController extends Controller
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

    $banks = bank::all();
    return view('banks.index', compact('banks'));
  }

  public function show_package()
  {
    $price_package_icons = pricePackageIcon::all();
    return view('price_package_icons.index', compact('price_package_icons'));
  }

    public function show_packages()
  {
    $price_packages = PricePackage::all();
    return view('price_packages.index', compact('price_packages'));
  }
}