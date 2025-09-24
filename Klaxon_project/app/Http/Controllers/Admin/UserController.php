<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
}
