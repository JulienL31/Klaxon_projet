<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get(['id','name','email','role']);
        return view('admin.users.index', compact('users'));
    }
}
