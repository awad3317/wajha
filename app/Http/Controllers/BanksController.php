<?php

namespace App\Http\Controllers;

use App\Models\bank;
use Illuminate\Http\Request;

class BanksController extends Controller
{
  public function index() {

        $banks = bank::all();
        return view('banks.index',compact('banks'));
        
    }
}