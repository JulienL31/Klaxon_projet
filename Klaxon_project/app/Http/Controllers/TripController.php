<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TripController extends Controller
{
    /**
     * Formulaire de création d'un trajet.
     */
    public function create()
    {
        $agencies = Agency::orderBy('name')->get();

        return view('trips.create', compact('agencies'));
    }

    /**
     * Enregistre un nouveau trajet.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'agency_from_id' => ['required', 'exists:agencies,id'],
            'agency_to_id'   => ['required', 'different:agency_from_id', 'exists:agencies,id'],
            'departure_date' => ['required', 'date'],
            'departure_time' => ['required'],
            'arrival_date'   => ['required', 'date'],
            'arrival_time'   => ['required'],
            'seats_total'    => ['required', 'integer', 'min:1'],
            'seats_free'     => ['required', 'integer', 'min:0', 'lte:seats_total'],
        ]);

        $departure = Carbon::parse($data['departure_date'].' '.$data['departure_time']);
        $arrival   = Carbon::parse($data['arrival_date'].' '.$data['arrival_time']);

        if ($arrival->lte($departure)) {
            return back()
                ->withErrors(['arrival_date' => 'On ne peut pas arriver avant de partir.'])
                ->withInput();
        }

        $user = auth()->user();

        Trip::create([
            'agency_from_id' => $data['agency_from_id'],
            'agency_to_id'   => $data['agency_to_id'],
            'departure_dt'   => $departure,
            'arrival_dt'     => $arrival,
            'seats_total'    => $data['seats_total'],
            'seats_free'     => $data['seats_free'],
            'contact_name'   => $user->name,
            'contact_email'  => $user->email,
            'contact_phone'  => $user->phone ?? null,
            'author_id'      => $user->id,
        ]);

        return redirect()->route('home')->with('status', 'Le trajet a été créé.');
    }

    /**
     * Formulaire d'édition (réservé à l'auteur ou à un admin).
     */
    public function edit(Trip $trip)
    {
        $this->authorizeAuthorOrAdmin($trip);

        $agencies = Agency::orderBy('name')->get();

        return view('trips.edit', compact('trip', 'agencies'));
    }

    /**
     * Met à jour un trajet (réservé à l'auteur ou à un admin).
     */
    public function update(Request $request, Trip $trip)
    {
        $this->authorizeAuthorOrAdmin($trip);

        $data = $request->validate([
            'agency_from_id' => ['required', 'exists:agencies,id'],
            'agency_to_id'   => ['required', 'different:agency_from_id', 'exists:agencies,id'],
            'departure_date' => ['required', 'date'],
            'departure_time' => ['required'],
            'arrival_date'   => ['required', 'date'],
            'arrival_time'   => ['required'],
            'seats_total'    => ['required', 'integer', 'min:1'],
            'seats_free'     => ['required', 'integer', 'min:0', 'lte:seats_total'],
        ]);

        $departure = Carbon::parse($data['departure_date'].' '.$data['departure_time']);
        $arrival   = Carbon::parse($data['arrival_date'].' '.$data['arrival_time']);

        if ($arrival->lte($departure)) {
            return back()
                ->withErrors(['arrival_date' => 'On ne peut pas arriver avant de partir.'])
                ->withInput();
        }

        $trip->update([
            'agency_from_id' => $data['agency_from_id'],
            'agency_to_id'   => $data['agency_to_id'],
            'departure_dt'   => $departure,
            'arrival_dt'     => $arrival,
            'seats_total'    => $data['seats_total'],
            'seats_free'     => $data['seats_free'],
        ]);

        return redirect()->route('home')->with('status', 'Le trajet a été modifié.');
    }

    /**
     * Supprime un trajet (réservé à l'auteur ou à un admin).
     */
    public function destroy(Trip $trip)
    {
        $this->authorizeAuthorOrAdmin($trip);

        $trip->delete();

        return redirect()->route('home')->with('status', 'Le trajet a été supprimé.');
    }

    /**
     * Autorise l'auteur du trajet ou un admin.
     */
    private function authorizeAuthorOrAdmin(Trip $trip): void
    {
        $user = auth()->user();

        $isAuthor = $user && $user->id === $trip->author_id;
        $isAdmin  = $user && (($user->role ?? 'user') === 'admin');

        if (!($isAuthor || $isAdmin)) {
            abort(403);
        }
    }
}
