<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:admin']);
    }

    public function index(): View
    {
        $agencies = Agency::orderBy('name')->paginate(12);
        return view('admin.agencies.index', compact('agencies'));
    }

    public function create(): View
    {
        return view('admin.agencies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:agencies,name'],
        ]);

        Agency::create($data);
        return redirect()->route('admin.agencies.index')
            ->with('status', 'Agence créée.');
    }

    public function edit(Agency $agency): View
    {
        return view('admin.agencies.edit', compact('agency'));
    }

    public function update(Request $request, Agency $agency): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:agencies,name,'.$agency->id],
        ]);

        $agency->update($data);
        return redirect()->route('admin.agencies.index')
            ->with('status', 'Agence mise à jour.');
    }

    public function destroy(Agency $agency): RedirectResponse
    {
        $agency->delete();
        return redirect()->route('admin.agencies.index')
            ->with('status', 'Agence supprimée.');
    }
}
