<?php
use App\Models\User;
use App\Models\Presence;
use App\Models\AbsenceReason;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'superadmin']);
    $this->actingAs($this->admin);
});

it('affiche la liste des présences', function () {
    $response = $this->get(route('presences'));
    $response->assertOk();
    $response->assertInertia(fn ($page) => $page->component('SuperAdmin/Presence/PresenceIndex'));
});

it('refuse l\'accès aux non-admin', function () {
    $user = User::factory()->create(['role' => 'user']);
    $this->actingAs($user);
    $response = $this->get(route('presences'));
    $response->assertForbidden(); // ou assertRedirect selon ta politique
});

it('peut créer une présence', function () {
    $user = User::factory()->create(['role' => 'user']);
    $data = [
        'user_id' => $user->id,
        'date' => now()->toDateString(),
        'absent' => false,
        'late' => false,
        'heure_arrivee' => '08:00',
        'heure_depart' => '17:00',
        'minutes_retard' => 0,
        'en_retard' => false,
        'absence_reason_id' => null, // ou un id valide si absent = true
    ];
    $response = $this->post(route('presences.store'), $data);
    $response->assertRedirect();
    $this->assertDatabaseHas('presences', ['user_id' => $user->id, 'date' => $data['date']]);
});

it('peut éditer une présence', function () {
    $presence = Presence::factory()->create();
    $response = $this->get(route('presences.edit', $presence));
    $response->assertOk();
    $response->assertInertia();
});



it('peut mettre à jour une présence absente', function () {
    $presence = Presence::factory()->create(['absent' => 0]);
    $reason = AbsenceReason::factory()->create(); // <-- Ajout de cette ligne
    
    $data = [
        'user_id' => $presence->user_id,
        'date' => $presence->date,
        'absent' => 1,
        'late' => 0,
        'heure_arrivee' => null,
        'heure_depart' => null,
        'minutes_retard' => 0,
        'en_retard' => 0,
        'absence_reason_id' => $reason->id, // <-- Utilisation de $reason
    ];
    
    $response = $this->put(route('presences.update', $presence), $data);
    $response->assertRedirect();
    $this->assertDatabaseHas('presences', ['id' => $presence->id, 'absent' => 1]);
});


it('peut supprimer une présence', function () {
    $presence = Presence::factory()->create();
    $response = $this->delete(route('presences.destroy', $presence));
    $response->assertRedirect();
    $this->assertDatabaseMissing('presences', ['id' => $presence->id]);
});

