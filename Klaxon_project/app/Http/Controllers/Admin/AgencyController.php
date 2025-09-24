<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class AgencyController extends Controller
{
    /**
     * Liste des agences (recherche + pagination) avec compteurs de trajets.
     */
    public function index(Request $request): View
    {
        $agencies = Agency::query()
            ->when(
                $request->filled('q'),
                fn ($q) => $q->where('name', 'like', '%'.$request->string('q')->toString().'%')
            )
            ->withCount([
                'departingTrips', // hasMany(Trip::class, 'agency_from_id')
                'arrivingTrips',  // hasMany(Trip::class, 'agency_to_id')
            ])
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.agencies.index', compact('agencies'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        return view('admin.agencies.create');
    }

    /**
     * Enregistrement d'une agence.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:agencies,name'],
        ]);

        Agency::create($validated);

        return redirect()
            ->route('admin.agencies.index')
            ->with('status', 'Agence créée avec succès.');
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Agency $agency): View
    {
        return view('admin.agencies.edit', compact('agency'));
    }

    /**
     * Mise à jour d'une agence.
     */
    public function update(Request $request, Agency $agency): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('agencies', 'name')->ignore($agency->id),
            ],
        ]);

        $agency->update($validated);

        return redirect()
            ->route('admin.agencies.index')
            ->with('status', 'Agence mise à jour.');
    }

    /**
     * Suppression d'une agence (protégée si des trajets y sont rattachés).
     */
    public function destroy(Agency $agency): RedirectResponse
    {
        $hasTrips =
            $agency->departingTrips()->exists() ||
            $agency->arrivingTrips()->exists();

        if ($hasTrips) {
            return back()->with('status', "Impossible de supprimer : des trajets utilisent cette agence.");
        }

        $agency->delete();

        return redirect()
            ->route('admin.agencies.index')
            ->with('status', 'Agence supprimée.');
    }
}
