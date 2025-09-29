<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Trip;
use App\Models\User as AppUser;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur des trajets (côté utilisateurs authentifiés).
 */
class TripController extends Controller
{
    /**
     * Auth requis pour les opérations d'écriture.
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Formulaire de création d'un trajet.
     */
    public function create(): View
    {
        $agencies = Agency::query()->orderBy('name')->get();

        return view('trips.create', compact('agencies'));
    }

    /**
     * Enregistre un nouveau trajet.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'agency_from_id' => ['required', 'exists:agencies,id'],
            'agency_to_id'   => ['required', 'exists:agencies,id', 'different:agency_from_id'],
            'departure_date' => ['required', 'date'],
            'departure_time' => ['required', 'date_format:H:i'],
            'arrival_date'   => ['required', 'date', 'after_or_equal:departure_date'],
            'arrival_time'   => ['required', 'date_format:H:i'],
            'seats_total'    => ['required', 'integer', 'min:1'],
            'seats_free'     => ['required', 'integer', 'min:0', 'lte:seats_total'],
        ]);

        // Construit des DateTime complets (secondes à 0 pour cohérence).
        $departure = Carbon::createFromFormat('Y-m-d H:i', $validated['departure_date'].' '.$validated['departure_time'])->seconds(0);
        $arrival   = Carbon::createFromFormat('Y-m-d H:i', $validated['arrival_date'].' '.$validated['arrival_time'])->seconds(0);

        // Contrôle supplémentaire : on ne peut pas arriver avant (ou au même instant que) le départ.
        if ($arrival->lte($departure)) {
            return back()
                ->withErrors(['arrival_time' => 'L’heure d’arrivée doit être postérieure à l’heure de départ.'])
                ->withInput();
        }

        /** @var AppUser|null $user */
        $user = Auth::user();

        Trip::create([
            'agency_from_id' => $validated['agency_from_id'],
            'agency_to_id'   => $validated['agency_to_id'],
            'departure_at'   => $departure,
            'arrival_at'     => $arrival,
            'seats_total'    => $validated['seats_total'],
            'seats_free'     => $validated['seats_free'],
            'author_id'      => (int) Auth::id(),
            'contact_name'   => $user?->name,
            'contact_email'  => $user?->email,
            'contact_phone'  => $user?->phone,
        ]);

        return redirect()->route('home')->with('status', 'Trajet créé avec succès.');
    }

    /**
     * Formulaire d’édition d’un trajet existant.
     */
    public function edit(Trip $trip): View
    {
        $this->authorizeAuthorOrAdmin($trip);

        $agencies = Agency::query()->orderBy('name')->get();

        return view('trips.edit', compact('trip', 'agencies'));
    }

    /**
     * Met à jour un trajet.
     */
    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $this->authorizeAuthorOrAdmin($trip);

        $validated = $request->validate([
            'agency_from_id' => ['required', 'exists:agencies,id'],
            'agency_to_id'   => ['required', 'exists:agencies,id', 'different:agency_from_id'],
            'departure_date' => ['required', 'date'],
            'departure_time' => ['required', 'date_format:H:i'],
            'arrival_date'   => ['required', 'date', 'after_or_equal:departure_date'],
            'arrival_time'   => ['required', 'date_format:H:i'],
            'seats_total'    => ['required', 'integer', 'min:1'],
            'seats_free'     => ['required', 'integer', 'min:0', 'lte:seats_total'],
        ]);

        $departure = Carbon::createFromFormat('Y-m-d H:i', $validated['departure_date'].' '.$validated['departure_time'])->seconds(0);
        $arrival   = Carbon::createFromFormat('Y-m-d H:i', $validated['arrival_date'].' '.$validated['arrival_time'])->seconds(0);

        if ($arrival->lte($departure)) {
            return back()
                ->withErrors(['arrival_time' => 'L’heure d’arrivée doit être postérieure à l’heure de départ.'])
                ->withInput();
        }

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
     */
    public function destroy(Trip $trip): RedirectResponse
    {
        $this->authorizeAuthorOrAdmin($trip);

        $trip->delete();

        return redirect()->route('home')->with('status', 'Trajet supprimé avec succès.');
    }

    /**
     * Autorisation : admin ou auteur du trajet.
     */
    private function authorizeAuthorOrAdmin(Trip $trip): void
    {
        /** @var AppUser|null $user */
        $user   = Auth::user();
        $userId = $user?->id ? (int) $user->id : 0;
        $role   = (string) ($user?->role ?? 'user');

        if ($role !== 'admin' && (int) $trip->author_id !== $userId) {
            abort(403, 'Non autorisé.');
        }
    }
}
