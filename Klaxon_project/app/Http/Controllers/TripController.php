<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create','store','edit','update','destroy']);
    }

    public function create(): View
    {
        $agencies = Agency::orderBy('name')->get();
        return view('trips.create', compact('agencies'));
    }

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
            // ⬇️ Alignement avec la BDD
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

    public function edit(Trip $trip): View
    {
        $this->authorizeAuthorOrAdmin($trip);
        $agencies = Agency::orderBy('name')->get();
        return view('trips.edit', compact('trip','agencies'));
    }

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
            // ⬇️ Alignement avec la BDD
            'departure_at'   => $departure,
            'arrival_at'     => $arrival,
            'seats_total'    => $validated['seats_total'],
            'seats_free'     => $validated['seats_free'],
        ]);

        return redirect()->route('home')->with('status', 'Trajet mis à jour avec succès.');
    }

    public function destroy(Trip $trip): RedirectResponse
    {
        $this->authorizeAuthorOrAdmin($trip);
        $trip->delete();

        return redirect()->route('home')->with('status', 'Trajet supprimé avec succès.');
    }

    private function authorizeAuthorOrAdmin(Trip $trip): void
    {
        $user = Auth::user();
        if (!$user || (($user->role ?? 'user') !== 'admin' && $trip->author_id !== $user->id)) {
            abort(403, 'Non autorisé.');
        }
    }
}
