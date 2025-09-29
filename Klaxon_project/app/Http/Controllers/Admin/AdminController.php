<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Contracts\View\View;

/**
 * Tableau de bord administrateur.
 */
class AdminController extends Controller
{
    /**
     * Page d'accueil de l'admin.
     *
     * @return View
     */
    public function index(): View
    {
        $counts = [
            'users'    => User::query()->count(),
            'agencies' => Agency::query()->count(),
            'trips'    => Trip::query()->count(),
        ];

        return view('admin.index', compact('counts'));
    }
}
