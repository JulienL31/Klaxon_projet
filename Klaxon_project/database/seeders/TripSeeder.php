<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::first() ?? User::factory()->create(['email' => 'demo@entreprise.com']);

        $paris = Agency::where('name', 'Paris')->first();
        $lyon  = Agency::where('name', 'Lyon')->first();
        $toul  = Agency::where('name', 'Toulouse')->first();
        $nantes= Agency::where('name', 'Nantes')->first();

        if (! $paris || ! $lyon) return;

        $now = now()->startOfHour();

        $rows = [
            [
                'from' => $paris->id, 'to' => $lyon->id,
                'dep' => $now->copy()->addDay()->setTime(8,0),
                'arr' => $now->copy()->addDay()->setTime(12,0),
                'tot' => 3, 'free' => 2,
            ],
            [
                'from' => $lyon->id, 'to' => $paris->id,
                'dep' => $now->copy()->addDays(2)->setTime(9,0),
                'arr' => $now->copy()->addDays(2)->setTime(13,0),
                'tot' => 4, 'free' => 1,
            ],
            [
                'from' => $paris->id, 'to' => optional($toul)->id ?? $lyon->id,
                'dep' => $now->copy()->addDays(3)->setTime(7,30),
                'arr' => $now->copy()->addDays(3)->setTime(12,15),
                'tot' => 2, 'free' => 1,
            ],
            [
                'from' => $lyon->id, 'to' => optional($nantes)->id ?? $paris->id,
                'dep' => $now->copy()->addDays(4)->setTime(10,0),
                'arr' => $now->copy()->addDays(4)->setTime(15,0),
                'tot' => 3, 'free' => 3,
            ],
        ];

        foreach ($rows as $r) {
            Trip::create([
                'agency_from_id' => $r['from'],
                'agency_to_id'   => $r['to'],
                'author_id'      => $author->id,
                'departure_at'   => $r['dep'],
                'arrival_at'     => $r['arr'],
                'seats_total'    => $r['tot'],
                'seats_free'     => $r['free'],
            ]);
        }
    }
}
