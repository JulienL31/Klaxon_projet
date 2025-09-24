<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Paris', 'Lyon', 'Marseille', 'Bordeaux', 'Nantes', 'Lille', 'Toulouse'];
        foreach ($names as $n) {
            Agency::firstOrCreate(['name' => $n]);
        }
    }
}
