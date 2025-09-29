<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Contracts\View\View;

/**
 * Accueil public : trajets à venir avec places disponibles.
 */
class HomeController extends Controller
{
    /**
     * Liste à afficher sur l'accueil.
     *
     * @return View
     */
    public function index(): View
    {
        $trips = Trip::query()
            ->with(['from', 'to', 'author'])
            ->where('departure_at', '>', now())
            ->where('seats_free', '>', 0)
            ->orderBy('departure_at', 'asc')
            ->get();

        return view('home.index', compact('trips'));
    }
}
