<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Consultation des trajets côté administrateur.
 */
class TripController extends Controller
{
    /**
     * Liste paginée des trajets (récents d'abord, ou tri adapté).
     *
     * @return View
     */
    public function index(): View
    {
        $trips = Trip::with(['from','to','author'])
            ->orderByDesc('departure_at')
            ->paginate(20);

        return view('admin.trips.index', compact('trips'));
    }

    /**
     * Suppression d'un trajet.
     *
     * @param  Trip  $trip
     * @return RedirectResponse
     */
    public function destroy(Trip $trip): RedirectResponse
    {
        $trip->delete();

        return back()->with('status', 'Trajet supprimé.');
    }
}
