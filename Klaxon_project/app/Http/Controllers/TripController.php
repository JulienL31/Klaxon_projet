<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur utilisateur pour la gestion des trajets.
 *
 * - create / store : création d'un trajet
 * - edit / update  : modification du trajet par son auteur (ou admin)
 * - destroy        : suppression par l'auteur (ou admin)
 */
class TripController extends Controller
{
    /**
     * Protège les actions d'écriture : utilisateur authentifié requis.
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['create','store','edit','update','destroy']);
    }

    /**
     * Affiche le formulaire de création de trajet.
     *
     * @return View
     */
    public function create(): View
    {
        $agencies = Agency::orderBy('name')->get();

        return view('trips.create', compact('agencies'));
    }

    /**
     * Enregistre un nouveau trajet.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'agency_from_id' => ['required','exists:agencies,id'],
            'agency_to_id'   => ['required','exists:agencies,id','different:agency_from_id'],
            'departure_date' => ['required','date'],
            'departure_time' => ['required','date_format:H:i'],
            'arrival_date'   => ['required','date','after_or_equal:departure_date'],
            'arrival_time'   => ['required','date_format:H:i'],
            'seats_total'    => ['required','integer','min:1'],
            'seats_free'     => ['required','integer','min:0','lte:seats_total'],
        ]);

        $departure = Carbon::createFromFormat('Y-m-d H:i', $validated['departure_date'].' '.$validated['departure_time'])->seconds(0);
        $arrival   = Carbon::createFromFormat('Y-m-d H:i', $validated['arrival_date'].' '.$validated['arrival_time'])->seconds(0);

        $user = Auth::user();

        Trip::create([
            'agency_from_id' => $validated['agency_from_id'],
            'agency_to_id'   => $validated['agency_to_id'],
            'departure_at'   => $departure,
            'arrival_at'     => $arrival,
            'seats_total'    => $validated['seats_total'],
            'seats_free'     => $validated['seats_free'],
            'author_id'      => $user->id,
            'contact_name'   => $user->name ?? null,
            'contact_email'  => $user->email ?? null,
            'contact_phone'  => $user->phone ?? null,
        ]);

        return redirect()->route('home')->with('status', 'Trajet créé avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'un trajet.
     *
     * @param  Trip  $trip
     * @return View
     */
    public function edit(Trip $trip): View
    {
        $this->authorizeAuthorOrAdmin($trip);

        $agencies = Agency::orderBy('name')->get();

        return view('trips.edit', compact('trip','agencies'));
    }

    /**
     * Met à jour un trajet existant.
     *
     * @param  Request  $request
     * @param  Trip     $trip
     * @return RedirectResponse
     */
    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $this->authorizeAuthorOrAdmin($trip);

        $validated = $request->validate([
            'agency_from_id' => ['required','exists:agencies,id'],
            'agency_to_id'   => ['required','exists:agencies,id','different:agency_from_id'],
            'departure_date' => ['required','date'],
            'departure_time' => ['required','date_format:H:i'],
            'arrival_date'   => ['required','date','after_or_equal:departure_date'],
            'arrival_time'   => ['required','date_format:H:i'],
            'seats_total'    => ['required','integer','min:1'],
            'seats_free'     => ['required','integer','min:0','lte:seats_total'],
        ]);

        $departure = Carbon::createFromFormat('Y-m-d H:i', $validated['departure_date'].' '.$validated['departure_time'])->seconds(0);
        $arrival   = Carbon::createFromFormat('Y-m-d H:i', $validated['arrival_date'].' '.$validated['arrival_time'])->seconds(0);

        $trip->update([
            'agency_from_id' => $validated['agency_from_id'],
            'agency_to_id'   => $validated['agency_to_id'],
            'departure_at'   => $departure,
            'arrival_at'     => $arrival,
            'seats_total'    => $validated['seats_total'],
            'seats_free'     => $validated['seats_free'],
        ]);

        return redirect()->route('home')->with('status', 'Trajet mis à jour avec succès.');
    }

    /**
     * Supprime un trajet.
     *
     * @param  Trip  $trip
     * @return RedirectResponse
     */
    public function destroy(Trip $trip): RedirectResponse
    {
        $this->authorizeAuthorOrAdmin($trip);

        $trip->delete();

        return redirect()->route('home')->with('status', 'Trajet supprimé avec succès.');
    }

    /**
     * Autorise l'accès si l'utilisateur est admin ou auteur du trajet.
     *
     * @param  Trip  $trip
     * @return void
     */
    private function authorizeAuthorOrAdmin(Trip $trip): void
    {
        $user = Auth::user();

        if (!$user || (($user->role ?? 'user') !== 'admin' && $trip->author_id !== $user->id)) {
            abort(403, 'Non autorisé.');
        }
    }
}
