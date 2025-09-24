<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agency;
use App\Models\Trip;

class AdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users'    => User::count(),
            'agencies' => Agency::count(),
            'trips'    => Trip::count(),
        ];

        return view('admin.index', compact('stats'));
    }
}
