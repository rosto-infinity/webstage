<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Presence;
use Illuminate\Http\Request;
use App\Models\AbsenceReason;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\PresenceRequest;

class PresenceController extends Controller
{
   public function index()
{
    $presences = Presence::with('user', 'absenceReason')
        ->orderBy('date', 'desc')
        ->get()
        ->map(fn ($p) => [
            'id' => $p->id,
            'date' => $p->date,
            'arrival_time' => $p->arrival_time,
            'departure_time' => $p->departure_time,
            'late_minutes' => $p->late_minutes,
            'absent' => $p->absent,
            'late' => $p->late,
            'user' => [
                'name' => $p->user->name,
                'email' => $p->user->email,
            ],
            'absence_reason' => $p->absenceReason ? $p->absenceReason->name : null,
        ]);

    $presenceCount = Presence::count();

    return Inertia::render('SuperAdmin/Presence/PresenceIndex', [
        'presences' => $presences,
        'presenceCount' => $presenceCount,
        'flash' => [
            'success' => session('success'),
            'error' => session('error'),
            'warning' => session('warning'),
        ],
    ]);
}

    public function add()
    {
        $users = User::where('role', 'user')->orderBy('name')->get(['id', 'name', 'email']);
        $absenceReasons = AbsenceReason::all(['id', 'name']);

        return Inertia::render('SuperAdmin/Presence/PresenceAdd', compact('users', 'absenceReasons'));
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
    'late_minutes' => $request->absent ? 0 : $request->minutes_retard,
    'absent' => $request->absent,
    'late' => $request->absent ? false : $request->en_retard,
    'absence_reason_id' => $request->absent 
        ? $request->absence_reason_id 
        : null, // Bien géré
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

    return Inertia::render('SuperAdmin/Presence/PresenceEdit', [
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
        'late_minutes' => $request->absent ? 0 : $request->minutes_retard,
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
       
        $presence->delete();

        return redirect()->route('presences')
            ->with('success', 'Présence supprimée avec succès.');
    }
}


// public function store(PresenceRequest $request)
// {
//     try {
//         // Vérification d'unicité (redondante avec la validation mais sécurisée)
//         $existingPresence = Presence::where('user_id', $request->user_id)
//             ->whereDate('date', $request->date)
//             ->first();

//         if ($existingPresence) {
//             return redirect()->back()
//                 ->withInput()
//                 ->withErrors([
//                     'user_id' => "Une présence existe déjà pour cet étudiant le {$request->date->format('d/m/Y')}"
//                 ]);
//         }

//         // Transformation des données avant création
//         $presenceData = [
//             'user_id' => $request->user_id,
//             'date' => $request->date,
//             'absent' => $request->boolean('absent'),
//             'late' => $request->absent ? false : $request->boolean('en_retard'),
//             'arrival_time' => $request->absent ? null : $request->heure_arrivee,
//             'departure_time' => $request->absent ? null : $request->heure_depart,
//             'late_minutes' => $request->absent ? null : $request->minutes_retard,
//         ];

//         // Création avec gestion des erreurs DB
//         $presence = Presence::create($presenceData);

//         // Notification si nécessaire (à adapter)
//         if ($presence->absent) {
//             // Event::dispatch(new AbsenceMarked($presence));
//         }

//         return redirect()->route('presences')
//             ->with([
//                 'success' => 'Présence enregistrée !',
//                 'presence_id' => $presence->id // Optionnel pour suivi
//             ]);

//     } catch (\Exception $e) {
//         report($e); // Log l'erreur
//         return back()
//             ->withInput()
//             ->withErrors(['system' => 'Une erreur système est survenue. Veuillez réessayer.']);
//     }
// }
