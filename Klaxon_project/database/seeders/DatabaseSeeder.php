<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================
        // 1) Utilisateurs (avec phone)
        // ==========================
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin Principal',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '0600000001',
            ]
        );

        $names = [
            'Jean Dupont',
            'Marie Curie',
            'Paul Martin',
            'Lucie Bernard',
            'Hugo Leroy',
            'Nina Moreau',
        ];

        $users = [];
        foreach ($names as $i => $name) {
            $localPart = strtolower(str_replace(' ', '.', $name)); // jean.dupont
            $email = $localPart . '@example.com';

            $users[] = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'     => $name,
                    'password' => Hash::make('password'),
                    'role'     => 'user',
                    // 06 + ID-like padding (10 chiffres total)
                    'phone'    => '06' . str_pad((string) (10000000 + $i), 8, '0', STR_PAD_LEFT),
                ]
            );
        }

        // ==========================
        // 2) Agences
        // ==========================
        $agencyNames = [
            'Paris','Lyon','Marseille','Toulouse','Nice','Nantes','Strasbourg','Montpellier',
            'Bordeaux','Lille','Rennes','Reims','Saint-Étienne','Toulon','Grenoble','Dijon','Angers',
        ];

        $agencies = [];
        foreach ($agencyNames as $name) {
            $agencies[] = Agency::firstOrCreate(['name' => $name]);
        }

        // ==========================
        // 3) Trajets de démo (12 trajets prochains jours)
        // ==========================
        $authors = array_merge([$admin], $users);
        $rng = mt_rand(1000, 9999); // graine légère pour un peu d’aléa

        for ($d = 1; $d <= 12; $d++) {
            // Choisir 2 agences différentes
            [$from, $to] = $this->pickTwoDifferent($agencies);

            // Départ le jour $d, entre 07:00 et 10:30
            $depHour = [7,8,9,10][array_rand([7,8,9,10])];
            $depMin  = [0,30][array_rand([0,30])];

            $dep = now()->copy()->addDays($d)->setTime($depHour, $depMin)->startOfMinute();

            // Durée 2 à 6h
            $durationH = [2,3,4,5,6][array_rand([2,3,4,5,6])];
            $arr = $dep->copy()->addHours($durationH);

            $seatsTotal = [1,2,3,4][array_rand([1,2,3,4])];
            $seatsFree  = mt_rand(0, $seatsTotal);

            $author = $authors[array_rand($authors)];

            Trip::create([
                'agency_from_id' => $from->id,
                'agency_to_id'   => $to->id,
                'departure_at'   => $dep,
                'arrival_at'     => $arr,
                'seats_total'    => $seatsTotal,
                'seats_free'     => $seatsFree,
                'author_id'      => $author->id,
            ]);
        }

        // Petit rappel en console
        $this->command?->info('Comptes de connexion:');
        $this->command?->line(' - Admin  -> admin@example.com / password');
        $this->command?->line(' - User   -> jean.dupont@example.com / password');
    }

    /**
     * @param  array<int,\App\Models\Agency>  $agencies
     * @return array{\App\Models\Agency,\App\Models\Agency}
     */
    private function pickTwoDifferent(array $agencies): array
    {
        $count = count($agencies);
        if ($count < 2) {
            throw new \RuntimeException('Il faut au moins 2 agences pour créer des trajets.');
        }

        $i = array_rand($agencies);
        do {
            $j = array_rand($agencies);
        } while ($j === $i);

        return [$agencies[$i], $agencies[$j]];
    }
}
