<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

    public function store(Request $request)
    {
//         use App\Http\Requests\PresenceRequest;

// public function store(PresenceRequest $request)
// {
//     Presence::create($request->validated());
//     return redirect()->route('presences')->with('success', 'Enregistrement effectué.');
// }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i|after:heure_arrivee',
            'minutes_retard' => 'nullable|integer|min:0',
            'absent' => 'boolean',
            'en_retard' => 'boolean',
        ]);

        Presence::create([
            'user_id' => $validated['user_id'],
            'date' => $validated['date'],
            'arrival_time' => $validated['heure_arrivee'],
            'departure_time' => $validated['heure_depart'],
            'late_minutes' => $validated['minutes_retard'],
            'absent' => $validated['absent'],
            'late' => $validated['en_retard'],
        ]);

        return redirect()->route('presences')->with('success', 'Présence ajoutée avec succès.');
    }
}
