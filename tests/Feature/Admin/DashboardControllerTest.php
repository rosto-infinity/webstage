<?php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('refuse l\'accès aux non-admin', function () {
    $user = User::factory()->create(['role' => 'user']);
    $this->actingAs($user);
    $response = $this->get(route('dashboard.superadmin'));
    $response->assertForbidden(); // ou assertRedirect selon ta politique
});

it('affiche le dashboard superadmin', function () {
    $admin = User::factory()->create(['role' => 'superadmin']);
    $this->actingAs($admin);
    $response = $this->get(route('dashboard.superadmin'));
    $response->assertOk();
    $response->assertInertia();
});