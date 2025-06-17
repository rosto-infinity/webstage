<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\PresenceRequest;

class PresenceController extends Controller
{
    public function index()
    {
        $presences = Presence::with('user')
            ->orderBy('date', 'desc')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'date' => $p->date, // Formatage explicite en string
                'arrival_time' => $p->arrival_time,
                'departure_time' => $p->departure_time,
                'late_minutes' => $p->late_minutes,
                'absent' => $p->absent,
                'late' => $p->late,
                'user' => [
                    'name' => $p->user->name,
                    'email' => $p->user->email,
                ],
            ]);

        return Inertia::render('admin/Presence/PresenceIndex', compact('presences'));
    }

    public function add()
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return Inertia::render('admin/Presence/PresenceAdd', compact('users'));
    }

public function store(PresenceRequest $request)
{
    // Vérification finale avant création
    if (Presence::where('user_id', $request->user_id)
               ->whereDate('date', $request->date)
               ->exists()) {
        return back()
            ->withErrors(['user_id' => 'Une présence existe déjà pour cet étudiant aujourd\'hui.'])
            ->withInput();
    }

    // Création de la présence
    Presence::create([
        'user_id' => $request->user_id,
        'date' => $request->date,
        'arrival_time' => $request->absent ? null : $request->heure_arrivee,
        'departure_time' => $request->absent ? null : $request->heure_depart,
        'late_minutes' => $request->absent ? null : $request->minutes_retard,
        'absent' => $request->absent,
        'late' => $request->absent ? false : $request->en_retard,
    ]);

    return redirect()->route('presences')
        ->with('success', 'Présence enregistrée avec succès.');
}
    
public function edit($id)
{
    // Récupérer la présence par ID
    $presence = Presence::findOrFail($id);
    
    // Récupérer tous les utilisateurs pour le sélecteur
    $users = User::orderBy('name')->get(['id', 'name', 'email']);

    return Inertia::render('admin/Presence/PresenceEdit', [
        'presence' => $presence,
        'users' => $users,
    ]);
}

public function update(PresenceRequest $request, $id)
{
    // Récupérer la présence par ID
    $presence = Presence::findOrFail($id);
    
    // Mettre à jour la présence avec les données validées
    $presence->update([
        'user_id' => $request->user_id,
        'date' => $request->date,
        'arrival_time' => $request->absent ? null : $request->heure_arrivee,
        'departure_time' => $request->absent ? null : $request->heure_depart,
        'late_minutes' => $request->absent ? null : $request->minutes_retard,
        'absent' => $request->absent,
        'late' => $request->absent ? false : $request->en_retard,
    ]);

    return redirect()->route('presences')->with('success', 'Présence mise à jour avec succès.');
}
  /**
     * Supprime une présence
     */
    public function destroy(Presence $presence)
    {
        // Vérification des autorisations si nécessaire
        // $this->authorize('delete', $presence);

        $presence->delete();

        return redirect()->route('presences')
            ->with('success', 'Présence supprimée avec succès.');
    }
}