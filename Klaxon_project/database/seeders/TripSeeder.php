<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Trip;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $paris    = Agency::where('name','Paris')->first();
        $lyon     = Agency::where('name','Lyon')->first();
        $tls      = Agency::where('name','Toulouse')->first();
        $lille    = Agency::where('name','Lille')->first();
        $nantes   = Agency::where('name','Nantes')->first();

        $rows = [
            [$paris, $tls,  2, 7, 'Bob Martin','bob@example.com','06 01 02 03 04', 4, 2],
            [$tls,   $lyon, 3, 6, 'Alice Durand','alice@example.com','01 02 03 04 05', 3, 1],
            [$lyon,  $paris,5, 9, 'Chloé Petit','chloe@example.com', null, 2, 1],
            [$lille, $nantes,6, 10, 'Marc Simon','marc@example.com','07 00 00 00 00', 4, 3],
            [$nantes,$paris,8, 12, 'Nina Leroy','nina@example.com','05 55 55 55 55', 2, 1],
        ];

        foreach ($rows as [$from,$to,$dH,$aH,$cName,$cMail,$cPhone,$tot,$free]) {
            Trip::create([
                'agency_from_id' => $from->id,
                'agency_to_id'   => $to->id,
                'departure_dt'   => Carbon::now()->addDays( $dH )->setTime(8,0),
                'arrival_dt'     => Carbon::now()->addDays( $dH )->addHours($aH - $dH)->setTime(15,0),
                'seats_total'    => $tot,
                'seats_free'     => $free,       // > 0 pour s’afficher
                'contact_name'   => $cName,
                'contact_email'  => $cMail,
                'contact_phone'  => $cPhone,
            ]);
        }
    }

    public function run(): void
{
    $this->call([
        AgencySeeder::class,
        TripSeeder::class,
    ]);
}

}

