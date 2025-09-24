<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $trips = Trip::with(['from','to'])
            ->where('departure_at', '>', now())     
            ->where('seats_free', '>', 0)
            ->orderBy('departure_at', 'asc')        
            ->get();

        return view('home.index', compact('trips'));
    }
}
