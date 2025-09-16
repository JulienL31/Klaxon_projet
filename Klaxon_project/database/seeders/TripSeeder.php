<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        // Comptes de test si absents
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );
        $user  = User::firstOrCreate(
            ['email' => 'user@example.com'],
            ['name' => 'User', 'password' => Hash::make('password')]
        );

        // Agences requises (au cas où AgencySeeder n'a pas tourné)
        foreach (['Paris','Lyon','Toulouse','Lille','Nantes'] as $n) {
            Agency::firstOrCreate(['name' => $n]);
        }

        $P = Agency::where('name','Paris')->first();
        $L = Agency::where('name','Lyon')->first();
        $T = Agency::where('name','Toulouse')->first();
        $Li= Agency::where('name','Lille')->first();
        $N = Agency::where('name','Nantes')->first();

        $rows = [
            [$P,$T,2,7,'Bob User','user@example.com','06 01 02 03 04',$user->id,4,2],
            [$T,$L,3,6,'Alice Admin','admin@example.com','01 02 03 04 05',$admin->id,3,1],
            [$L,$P,5,9,'Chloé Petit','chloe@example.com',null,$user->id,2,1],
            [$Li,$N,6,10,'Marc Simon','marc@example.com','07 00 00 00 00',$admin->id,4,3],
            [$N,$P,8,12,'Nina Leroy','nina@example.com','05 55 55 55 55',$user->id,2,1],
        ];

        foreach ($rows as [$from,$to,$d,$a,$cName,$cMail,$cPhone,$author,$tot,$free]) {
            Trip::updateOrCreate(
                [
                    'agency_from_id' => $from->id,
                    'agency_to_id'   => $to->id,
                    'departure_dt'   => Carbon::now()->addDays($d)->setTime(8,0),
                ],
                [
                    'arrival_dt'     => Carbon::now()->addDays($d)->addHours(max(1,$a-$d))->setTime(15,0),
                    'seats_total'    => $tot,
                    'seats_free'     => $free,
                    'contact_name'   => $cName,
                    'contact_email'  => $cMail,
                    'contact_phone'  => $cPhone,
                    'author_id'      => $author,
                ]
            );
        }
    }
}
