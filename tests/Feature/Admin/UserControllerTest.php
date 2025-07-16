<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'superadmin']);
    $this->actingAs($this->admin);
});

it('affiche la liste des utilisateurs', function () {
    $response = $this->get(route('users.index'));
    $response->assertOk();
    // Correction ici : adapte le nom du composant à celui retourné par ton contrôleur
    $response->assertInertia(fn ($page) => $page->component('SuperAdmin/Users/UserIndex'));
});

it('refuse l\'accès aux non-admin', function () {
    $user = User::factory()->create(['role' => 'user']);
    $this->actingAs($user);
    $response = $this->get(route('users.index'));
    $response->assertForbidden(); // ou assertRedirect selon ta politique
});

it('peut créer un utilisateur', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];
    $response = $this->post(route('users.store'), $data);
    $response->assertRedirect();
    $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
});

it('peut éditer un utilisateur', function () {
    $user = User::factory()->create();
    $response = $this->get(route('users.edit', $user));
    $response->assertOk();
    // Adapte ici aussi si besoin
    $response->assertInertia(fn ($page) => $page->component('SuperAdmin/Users/UserEdit'));
});

it('peut mettre à jour un utilisateur', function () {
    $user = User::factory()->create();
    $response = $this->put(route('users.update', $user), [
        'name' => 'Updated Name',
        'email' => $user->email,
    ]);
    $response->assertRedirect();
    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
});

it('peut supprimer un utilisateur', function () {
    $user = User::factory()->create();
    $response = $this->delete(route('users.destroy', $user));
    $response->assertRedirect();
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});