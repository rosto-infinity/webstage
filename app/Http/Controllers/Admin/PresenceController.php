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
                'date' => $p->date, // en string
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
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'arrival_time' => 'nullable|date_format:H:i',
            'departure_time' => 'nullable|date_format:H:i',
            'late_minutes' => 'nullable|integer|min:0',
            'absent' => 'required|boolean',
            'late' => 'required|boolean',
        ]);

        Presence::create($data);

        return redirect()->route('presences.create')->with('success', 'Présence ajoutée avec succès.');
    }
}
