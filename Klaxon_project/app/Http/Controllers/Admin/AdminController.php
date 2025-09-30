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
            'users'    => User::query()->count(),
            'agencies' => Agency::query()->count(),
            'trips'    => Trip::query()->count(),
        ];

        $trips = Trip::query()
            ->with(['from', 'to', 'author'])
            ->orderBy('departure_at', 'asc')
            ->paginate(self::PER_PAGE);

        $trips->appends(request()->query());

        return view('admin.index', [
            'counts' => $counts,
            'trips'  => $trips,
        ]);
    }
}
