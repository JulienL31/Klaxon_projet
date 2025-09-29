<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TripUpdateAuthTest extends TestCase
{
    use RefreshDatabase;

    private Agency $a1;
    private Agency $a2;
    private User $author;
    private User $other;
    private User $admin;
    private Trip $trip;

    protected function setUp(): void
    {
        parent::setUp();

        $this->a1 = Agency::query()->create(['name' => 'Paris']);
        $this->a2 = Agency::query()->create(['name' => 'Lyon']);

        $this->author = User::query()->create([
            'name' => 'Author',
            'email' => 'author@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '0611111111',
        ]);

        $this->other = User::query()->create([
            'name' => 'Other',
            'email' => 'other@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '0622222222',
        ]);

        $this->admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0633333333',
        ]);

        $this->trip = Trip::query()->create([
            'agency_from_id' => $this->a1->id,
            'agency_to_id'   => $this->a2->id,
            'departure_at'   => now()->addDay()->setTime(8, 0),
            'arrival_at'     => now()->addDay()->setTime(10, 0),
            'seats_total'    => 3,
            'seats_free'     => 2,
            'author_id'      => $this->author->id,
            'contact_name'   => $this->author->name,
            'contact_email'  => $this->author->email,
            'contact_phone'  => $this->author->phone,
        ]);
    }

    public function test_author_can_update(): void
    {
        $this->actingAs($this->author);

        $payload = [
            'agency_from_id' => $this->a1->id,
            'agency_to_id'   => $this->a2->id,
            'departure_date' => now()->addDays(2)->format('Y-m-d'),
            'departure_time' => '09:00',
            'arrival_date'   => now()->addDays(2)->format('Y-m-d'),
            'arrival_time'   => '11:00',
            'seats_total'    => 4,
            'seats_free'     => 3,
        ];

        $res = $this->put(route('trips.update', $this->trip), $payload);
        $res->assertRedirect(route('home'));

        $this->trip->refresh();
        $this->assertSame(4, $this->trip->seats_total);
        $this->assertSame(3, $this->trip->seats_free);
    }

    public function test_non_author_cannot_update(): void
    {
        $this->actingAs($this->other);

        $payload = [
            'agency_from_id' => $this->a1->id,
            'agency_to_id'   => $this->a2->id,
            'departure_date' => now()->addDays(2)->format('Y-m-d'),
            'departure_time' => '09:00',
            'arrival_date'   => now()->addDays(2)->format('Y-m-d'),
            'arrival_time'   => '11:00',
            'seats_total'    => 4,
            'seats_free'     => 3,
        ];

        $res = $this->put(route('trips.update', $this->trip), $payload);
        $res->assertStatus(403);
    }

    public function test_admin_can_update(): void
    {
        $this->actingAs($this->admin);

        $payload = [
            'agency_from_id' => $this->a1->id,
            'agency_to_id'   => $this->a2->id,
            'departure_date' => now()->addDays(3)->format('Y-m-d'),
            'departure_time' => '07:00',
            'arrival_date'   => now()->addDays(3)->format('Y-m-d'),
            'arrival_time'   => '09:00',
            'seats_total'    => 5,
            'seats_free'     => 2,
        ];

        $res = $this->put(route('trips.update', $this->trip), $payload);
        $res->assertRedirect(route('home'));

        $this->trip->refresh();
        $this->assertSame(5, $this->trip->seats_total);
        $this->assertSame(2, $this->trip->seats_free);
    }
}
