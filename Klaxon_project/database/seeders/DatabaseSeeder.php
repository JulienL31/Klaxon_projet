<?php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 *
 * Lance les seeders de base dans le bon ordre.
 * - Agencies d'abord
 * - Trips ensuite (dépend des agences)
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Exécution des seeders.
     */
    public function run(): void
    {
        $this->call([
            AgencySeeder::class,
            TripSeeder::class,
        ]);
    }
}

