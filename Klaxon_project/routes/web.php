<?php

use Illuminate\Support\Collection;

Route::get('/', function () {
    return view('home.index', ['trips' => collect()]);
})->name('home');

Route::get('/login', fn() => view('auth.login'))->name('login');