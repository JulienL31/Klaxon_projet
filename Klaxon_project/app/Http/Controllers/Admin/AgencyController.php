<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function index()
    {
        $agencies = Agency::orderBy('name')->get();
        return view('admin.agencies.index', compact('agencies'));
    }

    public function create()
    {
        return view('admin.agencies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required','string','max:100','unique:agencies,name']]);
        Agency::create($data);
        return redirect()->route('admin.agencies.index')->with('status','Agence créée.');
    }

    public function edit(Agency $agency)
    {
        return view('admin.agencies.edit', compact('agency'));
    }

    public function update(Request $request, Agency $agency)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:agencies,name,'.$agency->id],
        ]);
        $agency->update($data);
        return redirect()->route('admin.agencies.index')->with('status','Agence mise à jour.');
    }

    public function destroy(Agency $agency)
    {
        $agency->delete();
        return redirect()->route('admin.agencies.index')->with('status','Agence supprimée.');
    }
}
