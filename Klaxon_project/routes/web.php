<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

/* Accueil (public) */
Route::get('/', [HomeController::class, 'index'])->name('home');

/* Auth */
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/* Debug (local seulement) */
if (app()->environment('local')) {
    Route::get('/__debug', function () {
        DB::connection()->getPdo();
        return [
            'db' => DB::getDatabaseName(),
            'users' => DB::table('users')->count(),
            'agencies' => DB::table('agencies')->count(),
            'trips' => DB::table('trips')->count(),
            'trips_future' => DB::table('trips')
                ->where('departure_dt', '>', now())
                ->where('seats_free', '>', 0)
                ->count(),
        ];
    });
}
