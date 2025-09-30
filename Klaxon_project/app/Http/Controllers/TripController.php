<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    private const PER_PAGE = 15;

    /**
     * Liste des trajets (les plus récents d'abord).
     */
    public function index(Request $request): View
    {
        /** @var LengthAwarePaginator $trips */
        $trips = Trip::query()
            ->with(['from', 'to', 'author'])
            ->orderByDesc('departure_at')
            ->paginate(self::PER_PAGE);

        // Évite l’erreur PHPStan sur withQueryString()
        $trips->appends($request->query());

        return view('trips.index', compact('trips'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $agencies = Agency::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('trips.create', compact('agencies'));
    }

    /**
     * Enregistrement d’un trajet.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'agency_from_id' => ['required', 'integer', 'exists:agencies,id'],
            'agency_to_id'   => ['required', 'integer', 'different:agency_from_id', 'exists:agencies,id'],
            'departure_at'   => ['required', 'date'],
            'arrival_at'     => ['required', 'date', 'after:departure_at'], // <— rendu obligatoire
            'seats_total'    => ['required', 'integer', 'min:1'],
            'seats_free'     => ['required', 'integer', 'min:0', 'lte:seats_total'],
            'contact_name'   => ['nullable', 'string', 'max:255'],
            'contact_email'  => ['nullable', 'email', 'max:255'],
            'contact_phone'  => ['nullable', 'string', 'max:50'],
            // author_id peut être fixé automatiquement depuis l’utilisateur connecté
            'author_id'      => ['nullable', 'integer', 'exists:users,id'],
        ]);

        // Si non fourni, on prend l’utilisateur connecté
        $validated['author_id'] = $validated['author_id'] ?? (Auth::id() ?? 0);

        Trip::query()->create($validated);

        return redirect()
            ->route('trips.index')
            ->with('status', 'Trajet créé.');
    }

    /**
     * Détails d’un trajet.
     */
    public function show(Trip $trip): View
    {
        $trip->load(['from', 'to', 'author']);
        return view('trips.show', compact('trip'));
    }

    /**
     * Formulaire d’édition.
     */
    public function edit(Trip $trip): View
    {
        $agencies = Agency::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('trips.edit', compact('trip', 'agencies'));
    }

    /**
     * Mise à jour d’un trajet.
     */
    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $validated = $request->validate([
            'agency_from_id' => ['required', 'integer', 'exists:agencies,id'],
            'agency_to_id'   => ['required', 'integer', 'different:agency_from_id', 'exists:agencies,id'],
            'departure_at'   => ['required', 'date'],
            'arrival_at'     => ['required', 'date', 'after:departure_at'], // <— rendu obligatoire
            'seats_total'    => ['required', 'integer', 'min:1'],
            'seats_free'     => ['required', 'integer', 'min:0', 'lte:seats_total'],
            'contact_name'   => ['nullable', 'string', 'max:255'],
            'contact_email'  => ['nullable', 'email', 'max:255'],
            'contact_phone'  => ['nullable', 'string', 'max:50'],
            'author_id'      => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if (!isset($validated['author_id'])) {
            $validated['author_id'] = $trip->author_id; // on conserve l’existant
        }

        $trip->update($validated);

        return redirect()
            ->route('trips.index')
            ->with('status', 'Trajet mis à jour.');
    }

    /**
     * Suppression d’un trajet.
     */
    public function destroy(Trip $trip): RedirectResponse
    {
        $trip->delete();

        return redirect()
            ->route('trips.index')
            ->with('status', 'Trajet supprimé.');
    }
}
