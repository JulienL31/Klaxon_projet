<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Contracts\View\View;

class AdminController extends Controller
{
    private const PER_PAGE = 15;

    /**
     * Dashboard admin.
     */
    public function index(): View
    {
        $counts = [
            'users'    => User::count(),
            'agencies' => Agency::count(),
            'trips'    => Trip::count(),
        ];

        $trips = Trip::query()
            ->with(['from', 'to', 'author'])   // adapte les relations si nécessaire
            ->orderBy('departure_date')        // ou ->latest('departure_date')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.index', [
            'counts' => $counts,
            'trips'  => $trips,
        ]);
    }
}
