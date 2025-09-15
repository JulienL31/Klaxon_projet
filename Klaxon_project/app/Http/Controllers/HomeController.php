<?php

namespace App\Http\Controllers;

use App\Models\Trip;

class HomeController extends Controller
{
    public function index()
    {
        $trips = Trip::with(['from','to'])
            ->where('departure_dt','>', now())
            ->where('seats_free','>', 0)
            ->orderBy('departure_dt','asc')
            ->get();

        return view('home.index', compact('trips'));
    }
}
