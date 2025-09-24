<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use App\Models\Trip;

class HomeController extends Controller
{
    public function index(): View
    {
        $trips = Trip::with(['from', 'to'])
            ->upcoming()
            ->withFreeSeats()
            ->ordered()
            ->get();

        return view('home', compact('trips'));
    }
}
