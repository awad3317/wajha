<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Establishment;
use App\Repositories\EstablishmentRepository;

class EstablishmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private EstablishmentRepository $EstablishmentRepository)
    {
        $this->middleware('auth');
    }
   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $establishments = Establishment::latest()->paginate(10);
        return view('establishments.index',compact('establishments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $establishment = $establishment=$this->EstablishmentRepository->getById($id);
        return view('establishments.show_info',compact('establishment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}