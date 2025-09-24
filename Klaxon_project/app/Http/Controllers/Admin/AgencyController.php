<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function index(): View
    {
        $agencies = Agency::all();
        return view('admin.agencies.index', compact('agencies'));
    }

    public function create(): View
    {
        return view('admin.agencies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:agencies,name',
        ]);

        Agency::create($validated);

        return redirect()->route('admin.agencies.index')
            ->with('success', 'Agence créée avec succès.');
    }

    public function edit(Agency $agency): View
    {
        return view('admin.agencies.edit', compact('agency'));
    }

    public function update(Request $request, Agency $agency): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:agencies,name,' . $agency->id,
        ]);

        $agency->update($validated);

        return redirect()->route('admin.agencies.index')
            ->with('success', 'Agence mise à jour avec succès.');
    }

    public function destroy(Agency $agency): RedirectResponse
    {
        $agency->delete();

        return redirect()->route('admin.agencies.index')
            ->with('success', 'Agence supprimée avec succès.');
    }
}
