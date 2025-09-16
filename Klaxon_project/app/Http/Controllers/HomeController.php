<?php

namespace App\Http\Controllers;

use App\Models\Trip;

class HomeController extends Controller
{
    public function index()
    {
        $trips = Trip::with(['from', 'to'])
            ->where('departure_dt', '>', now())   // trajets futurs
            ->where('seats_free', '>', 0)         // places restantes
            ->orderBy('departure_dt', 'asc')
            ->get();

        return view('home.index', compact('trips'));
    }
}
