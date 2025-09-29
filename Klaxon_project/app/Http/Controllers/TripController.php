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
 * Gestion des trajets côté utilisateur.
 */
class TripController extends Controller
{
    /**
     * Auth requis pour les actions d'écriture.
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Formulaire de création.
     *
     * @return View
     */
    public function create(): View
    {
        $agencies = Agency::query()->orderBy('name')->get();

        return view('trips.create', compact('agencies'));
    }

    /**
     * Enregistre un trajet.
     *
     * @param  Request  $request
     * @return RedirectResponse
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

        $departure = Carbon::createFromFormat('Y-m-d H:i', $validated['departure_date'] . ' ' . $validated['departure_time'])->seconds(0);
        $arrival   = Carbon::createFromFormat('Y-m-d H:i', $validated['arrival_date'] . ' ' . $validated['arrival_time'])->seconds(0);

        $user = Auth::user();
        $authorId = Auth::id();
        $contactName  = $user instanceof AppUser ? $user->name  : null;
        $contactEmail = $user instanceof AppUser ? $user->email : null;
        $contactPhone = $user instanceof AppUser ? $user->phone : null;

        $trip = new Trip([
            'agency_from_id' => $validated['agency_from_id'],
            'agency_to_id'   => $validated['agency_to_id'],
            'departure_at'   => $departure,
            'arrival_at'     => $arrival,
            'seats_total'    => $validated['seats_total'],
            'seats_free'     => $validated['seats_free'],
            'author_id'      => $authorId,
            'contact_name'   => $contactName,
            'contact_email'  => $contactEmail,
            'contact_phone'  => $contactPhone,
        ]);
        $trip->save();

        return redirect()->route('home')->with('status', 'Trajet créé avec succès.');
    }

    /**
     * Formulaire d'édition.
     *
     * @param  Trip  $trip
     * @return View
     */
    public function edit(Trip $trip): View
    {
        $this->authorizeAuthorOrAdmin($trip);

        $agencies = Agency::query()->orderBy('name')->get();

        return view('trips.edit', compact('trip', 'agencies'));
    }

    /**
     * Mise à jour d'un trajet.
     *
     * @param  Request  $request
     * @param  Trip     $trip
     * @return RedirectResponse
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

        $departure = Carbon::createFromFormat('Y-m-d H:i', $validated['departure_date'] . ' ' . $validated['departure_time'])->seconds(0);
        $arrival   = Carbon::createFromFormat('Y-m-d H:i', $validated['arrival_date'] . ' ' . $validated['arrival_time'])->seconds(0);

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
     * Suppression d'un trajet.
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
     * Autorise si admin ou auteur.
     *
     * @param  Trip  $trip
     * @return void
     */
    private function authorizeAuthorOrAdmin(Trip $trip): void
    {
        $user = Auth::user();
        $userId = $user instanceof AppUser ? $user->id : null;
        $role   = $user instanceof AppUser ? ($user->role ?? 'user') : 'user';

        if ($role !== 'admin' && $trip->author_id !== $userId) {
            abort(403, 'Non autorisé.');
        }
    }
}
