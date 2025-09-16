<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgencyController as AdminAgencyController;
use App\Http\Controllers\Admin\UserController   as AdminUserController;
use App\Http\Controllers\Admin\TripController   as AdminTripController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| - Accueil public : trajets futurs avec places > 0
| - Auth : login (GET/POST) + logout (POST)
| - Trips (CRUD partiel) : créer / éditer / supprimer (auth requis)
| - Debug : /__debug accessible uniquement en environnement local
|--------------------------------------------------------------------------
*/

// Accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Trips (utilisateur connecté)
Route::middleware('auth')->group(function () {
    Route::resource('trips', TripController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
    // Noms générés : trips.create, trips.store, trips.edit, trips.update, trips.destroy
});

Route::middleware(['auth','can:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');

        // Utilisateurs (liste)
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');

        // Agences (CRUD sans show)
        Route::resource('agencies', AdminAgencyController::class)->except(['show']);

        // Trajets (liste + suppression)
        Route::get('/trips', [AdminTripController::class, 'index'])->name('trips.index');
        Route::delete('/trips/{trip}', [AdminTripController::class, 'destroy'])->name('trips.destroy');
    });
    
// Debug (local seulement)
if (app()->environment('local')) {
    Route::get('/__debug', function () {
        try {
            DB::connection()->getPdo(); // force la connexion

            $counts = [
                'users'        => DB::table('users')->count(),
                'agencies'     => DB::table('agencies')->count(),
                'trips'        => DB::table('trips')->count(),
                'trips_future' => DB::table('trips')
                    ->where('departure_dt', '>', now())
                    ->where('seats_free', '>', 0)
                    ->count(),
            ];

            return response()->json([
                'db'                => DB::getDatabaseName(),
                'counts'            => $counts,
                'first_trip_sample' => DB::table('trips')->orderBy('departure_dt')->first(),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
}
