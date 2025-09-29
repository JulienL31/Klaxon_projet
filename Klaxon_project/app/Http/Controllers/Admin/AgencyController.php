<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * CRUD des agences pour l'administrateur.
 */
class AgencyController extends Controller
{
    /**
     * Liste les agences.
     *
     * @return View
     */
    public function index(): View
    {
        $agencies = Agency::orderBy('name')->paginate(20);

        return view('admin.agencies.index', compact('agencies'));
    }

    /**
     * Formulaire de création d'une agence.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.agencies.create');
    }

    /**
     * Enregistre une nouvelle agence.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:agencies,name'],
        ]);

        Agency::create($data);

        return redirect()->route('admin.agencies.index')->with('status', 'Agence créée.');
    }

    /**
     * Formulaire d'édition d'une agence.
     *
     * @param  Agency  $agency
     * @return View
     */
    public function edit(Agency $agency): View
    {
        return view('admin.agencies.edit', compact('agency'));
    }

    /**
     * Met à jour une agence.
     *
     * @param  Request  $request
     * @param  Agency   $agency
     * @return RedirectResponse
     */
    public function update(Request $request, Agency $agency): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:agencies,name,'.$agency->id],
        ]);

        $agency->update($data);

        return redirect()->route('admin.agencies.index')->with('status', 'Agence mise à jour.');
    }

    /**
     * Supprime une agence.
     *
     * @param  Agency  $agency
     * @return RedirectResponse
     */
    public function destroy(Agency $agency): RedirectResponse
    {
        $agency->delete();

        return back()->with('status', 'Agence supprimée.');
    }
}
