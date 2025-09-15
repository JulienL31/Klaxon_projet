<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Paris','Lyon','Toulouse','Lille','Nantes'] as $name) {
            Agency::firstOrCreate(['name' => $name]);
        }
    }
}
