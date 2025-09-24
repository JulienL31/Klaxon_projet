<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Trip;

class TripController extends Controller
{
    public function index(): View
    {
        $trips = Trip::with(['from', 'to', 'author'])->get();

        return view('admin.trips.index', compact('trips'));
    }

    public function destroy(Trip $trip): RedirectResponse
    {
        $trip->delete();

        return redirect()->route('admin.trips.index')
            ->with('success', 'Trajet supprimé avec succès.');
    }
}
