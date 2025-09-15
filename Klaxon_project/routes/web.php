<?php

use Illuminate\Support\Collection;

Route::get('/', function () {
    return view('home.index', ['trips' => collect()]);
})->name('home');

Route::get('/login', fn() => view('auth.login'))->name('login');


Route::get('/__debug', function () {
    try {
        $db = \DB::connection()->getDatabaseName();

        $counts = [
            'users'        => \DB::table('users')->count(),
            'agencies'     => \DB::table('agencies')->count(),
            'trips'        => \DB::table('trips')->count(),
            'trips_future' => \DB::table('trips')
                                 ->where('departure_dt','>', now())
                                 ->where('seats_free','>', 0)
                                 ->count(),
        ];

        $first = \DB::table('trips')->orderBy('departure_dt')->first();

        return response()->json([
            'db'     => $db,
            'counts' => $counts,
            'first_trip_sample' => $first,
        ]);
    } catch (\Throwable $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
