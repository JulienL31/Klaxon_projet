<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TripStoreTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Agency $a1;
    private Agency $a2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->a1 = Agency::query()->create(['name' => 'Paris']);
        $this->a2 = Agency::query()->create(['name' => 'Lyon']);

        $this->user = User::query()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '0612345678',
        ]);
    }

    public function test_user_can_create_valid_trip(): void
    {
        $this->actingAs($this->user);

        $payload = [
            'agency_from_id' => $this->a1->id,
            'agency_to_id'   => $this->a2->id,
            'departure_date' => now()->addDay()->format('Y-m-d'),
            'departure_time' => '08:30',
            'arrival_date'   => now()->addDay()->format('Y-m-d'),
            'arrival_time'   => '11:00',
            'seats_total'    => 3,
            'seats_free'     => 2,
        ];

        $res = $this->post(route('trips.store'), $payload);
        $res->assertRedirect(route('home'));

        $this->assertDatabaseHas('trips', [
            'agency_from_id' => $this->a1->id,
            'agency_to_id'   => $this->a2->id,
            'seats_total'    => 3,
            'seats_free'     => 2,
            'author_id'      => $this->user->id,
        ]);

        $this->assertSame(1, Trip::query()->count());
    }

    public function test_validation_fails_when_same_agency(): void
    {
        $this->actingAs($this->user);

        $payload = [
            'agency_from_id' => $this->a1->id,
            'agency_to_id'   => $this->a1->id, // même agence -> erreur
            'departure_date' => now()->addDay()->format('Y-m-d'),
            'departure_time' => '08:30',
            'arrival_date'   => now()->addDay()->format('Y-m-d'),
            'arrival_time'   => '09:00',
            'seats_total'    => 3,
            'seats_free'     => 1,
        ];

        $res = $this->post(route('trips.store'), $payload);
        $res->assertSessionHasErrors(['agency_to_id']);
    }

    public function test_validation_fails_when_arrival_before_departure_date(): void
    {
        $this->actingAs($this->user);

        $payload = [
            'agency_from_id' => $this->a1->id,
            'agency_to_id'   => $this->a2->id,
            'departure_date' => now()->addDays(2)->format('Y-m-d'),
            'departure_time' => '10:00',
            'arrival_date'   => now()->addDay()->format('Y-m-d'), // avant la date de départ
            'arrival_time'   => '10:30',
            'seats_total'    => 2,
            'seats_free'     => 1,
        ];

        $res = $this->post(route('trips.store'), $payload);
        $res->assertSessionHasErrors(['arrival_date']);
    }

    public function test_validation_fails_when_seats_free_gt_total(): void
    {
        $this->actingAs($this->user);

        $payload = [
            'agency_from_id' => $this->a1->id,
            'agency_to_id'   => $this->a2->id,
            'departure_date' => now()->addDay()->format('Y-m-d'),
            'departure_time' => '08:00',
            'arrival_date'   => now()->addDay()->format('Y-m-d'),
            'arrival_time'   => '09:00',
            'seats_total'    => 2,
            'seats_free'     => 3, // > total
        ];

        $res = $this->post(route('trips.store'), $payload);
        $res->assertSessionHasErrors(['seats_free']);
    }
}
