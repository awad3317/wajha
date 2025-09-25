<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return $this->homepage();
    }
        public function homepage()
    {
        return view('homepage.index');
    }

    public function adminlog(){
        return view('adminlogs.index');
    }
    public function notification(){
        return view('notifications.index');
    }
    public function shearewajha(){
        return view('sheare-wajha');
    }
}