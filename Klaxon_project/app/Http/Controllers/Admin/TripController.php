<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::with(['from','to','author'])->orderBy('departure_dt')->get();
        return view('admin.trips.index', compact('trips'));
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        return redirect()->route('admin.trips.index')->with('status','Trajet supprimé.');
    }
}
