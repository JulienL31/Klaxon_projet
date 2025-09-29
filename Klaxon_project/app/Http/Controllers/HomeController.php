<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Contracts\View\View;

/**
 * Contrôleur de la page d'accueil publique.
 * Affiche les trajets futurs avec places disponibles, triés par départ croissant.
 */
class HomeController extends Controller
{
    /**
     * Liste des trajets à afficher sur l'accueil.
     *
     * @return View
     */
    public function index(): View
    {
        $trips = Trip::with(['from','to','author'])
            ->upcoming()
            ->withFreeSeats()
            ->ordered()
            ->get();

        return view('home.index', compact('trips'));
    }
}
