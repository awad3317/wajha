<?php

namespace App\Http\Controllers;

use App\Models\bank;
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
  public function index() {

        $banks = bank::all();
        return view('banks.index',compact('banks'));
        
    }
}