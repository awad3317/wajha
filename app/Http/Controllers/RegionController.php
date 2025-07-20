<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{

    public function index() {

        $regions = Region::latest()->paginate(5);
        return view('regions.index',compact('regions'));
        
    }
}