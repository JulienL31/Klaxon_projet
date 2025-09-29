<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AgencyCrudAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0600000000',
        ]);

        $this->user = User::query()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '0611111111',
        ]);
    }

    public function test_admin_can_create_edit_delete_agency(): void
    {
        $this->actingAs($this->admin);

        // Create
        $res = $this->post(route('admin.agencies.store'), ['name' => 'Bordeaux']);
        $res->assertRedirect(route('admin.agencies.index'));
        $this->assertDatabaseHas('agencies', ['name' => 'Bordeaux']);

        $agency = Agency::query()->where('name', 'Bordeaux')->firstOrFail();

        // Update
        $res = $this->put(route('admin.agencies.update', $agency), ['name' => 'Bordeaux Centre']);
        $res->assertRedirect(route('admin.agencies.index'));
        $this->assertDatabaseHas('agencies', ['id' => $agency->id, 'name' => 'Bordeaux Centre']);

        // Delete
        $res = $this->delete(route('admin.agencies.destroy', $agency));
        $res->assertRedirect();
        $this->assertDatabaseMissing('agencies', ['id' => $agency->id]);
    }

    public function test_user_cannot_access_admin_routes(): void
    {
        $this->actingAs($this->user);

        $this->get(route('admin.agencies.index'))->assertForbidden();
        $this->post(route('admin.agencies.store'), ['name' => 'Nice'])->assertForbidden();
    }
}
