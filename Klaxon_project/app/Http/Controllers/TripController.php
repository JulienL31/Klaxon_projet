<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trip;
use App\Models\Agency;

class TripController extends Controller
{
    public function create(): View
    {
        $agencies = Agency::all();
        return view('trips.create', compact('agencies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'agency_from_id' => 'required|exists:agencies,id',
            'agency_to_id'   => 'required|exists:agencies,id|different:agency_from_id',
            'departure_date' => 'required|date',
            'departure_time' => 'required|date_format:H:i',
            'arrival_date'   => 'required|date|after_or_equal:departure_date',
            'arrival_time'   => 'required|date_format:H:i',
            'seats_total'    => 'required|integer|min:1',
            'seats_free'     => 'required|integer|min:0|max:' . $request->seats_total,
        ]);

        $departure = $validated['departure_date'] . ' ' . $validated['departure_time'];
        $arrival   = $validated['arrival_date'] . ' ' . $validated['arrival_time'];

        Trip::create([
            'agency_from_id' => $validated['agency_from_id'],
            'agency_to_id'   => $validated['agency_to_id'],
            'departure_at'   => $departure,
            'arrival_at'     => $arrival,
            'seats_total'    => $validated['seats_total'],
            'seats_free'     => $validated['seats_free'],
            'author_id'      => Auth::id(),
        ]);

        return redirect()->route('home')
            ->with('success', 'Trajet créé avec succès.');
    }

    public function edit(Trip $trip): View
    {
        $this->authorizeAuthorOrAdmin($trip);

        $agencies = Agency::all();
        return view('trips.edit', compact('trip', 'agencies'));
    }

    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $this->authorizeAuthorOrAdmin($trip);

        $validated = $request->validate([
            'agency_from_id' => 'required|exists:agencies,id',
            'agency_to_id'   => 'required|exists:agencies,id|different:agency_from_id',
            'departure_date' => 'required|date',
            'departure_time' => 'required|date_format:H:i',
            'arrival_date'   => 'required|date|after_or_equal:departure_date',
            'arrival_time'   => 'required|date_format:H:i',
            'seats_total'    => 'required|integer|min:1',
            'seats_free'     => 'required|integer|min:0|max:' . $request->seats_total,
        ]);

        $departure = $validated['departure_date'] . ' ' . $validated['departure_time'];
        $arrival   = $validated['arrival_date'] . ' ' . $validated['arrival_time'];

        $trip->update([
            'agency_from_id' => $validated['agency_from_id'],
            'agency_to_id'   => $validated['agency_to_id'],
            'departure_at'   => $departure,
            'arrival_at'     => $arrival,
            'seats_total'    => $validated['seats_total'],
            'seats_free'     => $validated['seats_free'],
        ]);

        return redirect()->route('home')
            ->with('success', 'Trajet mis à jour avec succès.');
    }

    public function destroy(Trip $trip): RedirectResponse
    {
        $this->authorizeAuthorOrAdmin($trip);

        $trip->delete();

        return redirect()->route('home')
            ->with('success', 'Trajet supprimé avec succès.');
    }

    private function authorizeAuthorOrAdmin(Trip $trip): void
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $trip->author_id !== $user->id) {
            abort(403, 'Non autorisé.');
        }
    }
}
