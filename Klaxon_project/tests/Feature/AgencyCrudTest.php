<?php

use App\Models\User;
use App\Models\Agency;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function admin() {
    return User::factory()->create(['role' => 'admin']);
}

it('crée une agence (admin)', function () {
    $resp = $this->actingAs(admin())->post(route('admin.agencies.store'), ['name' => 'Bordeaux']);
    $resp->assertRedirect();
    $this->assertDatabaseHas('agencies', ['name' => 'Bordeaux']);
});

it('met à jour une agence (admin)', function () {
    $agency = Agency::create(['name' => 'Old']);
    $resp = $this->actingAs(admin())->put(route('admin.agencies.update', $agency), ['name' => 'New']);
    $resp->assertRedirect();
    $this->assertDatabaseHas('agencies', ['id' => $agency->id, 'name' => 'New']);
});

it('supprime une agence (admin)', function () {
    $agency = Agency::create(['name' => 'Tmp']);
    $resp = $this->actingAs(admin())->delete(route('admin.agencies.destroy', $agency));
    $resp->assertRedirect();
    $this->assertDatabaseMissing('agencies', ['id' => $agency->id]);
});
