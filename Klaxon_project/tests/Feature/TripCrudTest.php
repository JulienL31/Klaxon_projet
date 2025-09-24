<?php

use App\Models\User;
use App\Models\Agency;
use App\Models\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function authUser() {
    return User::factory()->create(['role' => 'user']);
}

it('crée un trajet', function () {
    $user = authUser();
    $from = Agency::create(['name' => 'Paris']);
    $to   = Agency::create(['name' => 'Lyon']);

    $resp = $this->actingAs($user)->post(route('trips.store'), [
        'agency_from_id' => $from->id,
        'agency_to_id'   => $to->id,
        'departure_date' => now()->addDay()->toDateString(),
        'departure_time' => '08:00',
        'arrival_date'   => now()->addDay()->toDateString(),
        'arrival_time'   => '12:00',
        'seats_total'    => 3,
        'seats_free'     => 2,
    ]);

    $resp->assertRedirect();
    $this->assertDatabaseHas('trips', [
        'agency_from_id' => $from->id,
        'agency_to_id'   => $to->id,
        'seats_total'    => 3,
        'seats_free'     => 2,
        'author_id'      => $user->id,
    ]);
});

it('met à jour un trajet (auteur)', function () {
    $user = authUser();
    $from = Agency::create(['name' => 'Paris']);
    $to   = Agency::create(['name' => 'Lyon']);

    $trip = Trip::factory()->create([
        'author_id'      => $user->id,
        'agency_from_id' => $from->id,
        'agency_to_id'   => $to->id,
        'departure_at'   => now()->addDays(2),
        'arrival_at'     => now()->addDays(2)->addHours(4),
        'seats_total'    => 3,
        'seats_free'     => 2,
    ]);

    $resp = $this->actingAs($user)->put(route('trips.update', $trip), [
        'agency_from_id' => $from->id,
        'agency_to_id'   => $to->id,
        'departure_date' => now()->addDays(3)->toDateString(),
        'departure_time' => '09:00',
        'arrival_date'   => now()->addDays(3)->toDateString(),
        'arrival_time'   => '13:00',
        'seats_total'    => 4,
        'seats_free'     => 3,
    ]);

    $resp->assertRedirect();
    $this->assertDatabaseHas('trips', [
        'id'            => $trip->id,
        'seats_total'   => 4,
        'seats_free'    => 3,
    ]);
});

it('supprime un trajet (auteur)', function () {
    $user = authUser();
    $trip = Trip::factory()->for($user, 'author')->create();

    $resp = $this->actingAs($user)->delete(route('trips.destroy', $trip));
    $resp->assertRedirect();
    $this->assertDatabaseMissing('trips', ['id' => $trip->id]);
});
