<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use App\Models\Agency;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:admin']);
    }

    public function index()
    {
        $counts = [
            'users'        => User::count(),
            'agencies'     => Agency::count(),
            'trips'        => Trip::count(),
            'future_trips' => Trip::where('departure_at', '>', now())->count(),
        ];

        // Derniers trajets (affichage résumé)
        $trips = Trip::with(['from', 'to', 'author'])
            ->orderBy('departure_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.index', compact('counts', 'trips'));
    }
}
