<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

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
    // Validation des données
    $validated = $request->validate([
        'user_id' => [
            'required',
            'exists:users,id',
            Rule::unique('presences')->where(function ($query) use ($request) {
                return $query->whereDate('date', $request->date);
            })
        ],
        'date' => [
            'required',
            'date',
            'before_or_equal:today'
        ],
        'heure_arrivee' => [
            'required_if:absent,false',
            'nullable',
            'date_format:H:i',
            function ($attribute, $value, $fail) use ($request) {
                if ($value && $request->absent) {
                    $fail("Incohérence : heure d'arrivée renseignée alors que marqué absent.");
                }
            }
        ],
        'heure_depart' => [
            'required_with:heure_arrivee',
            'nullable',
            'date_format:H:i',
            'after:heure_arrivee',
            function ($attribute, $value, $fail) use ($request) {
                if ($value && !$request->heure_arrivee) {
                    $fail("Le départ nécessite une heure d'arrivée.");
                }
                if ($value && $request->absent) {
                    $fail("Incohérence : heure de départ renseignée alors que marqué absent.");
                }
            }
        ],
        'minutes_retard' => [
            'nullable',
            'integer',
            'min:0',
            'max:300',
            'required_if:en_retard,true',
            function ($attribute, $value, $fail) use ($request) {
                if ($value && $request->absent) {
                    $fail("Incohérence : retard renseigné alors que marqué absent.");
                }
            }
        ],
        'absent' => 'required|boolean',
        'en_retard' => [
            'required',
            'boolean',
            'exclude_if:absent,true'
        ]
    ], [
        'user_id.unique' => 'Cet étudiant a déjà une présence enregistrée pour cette date.',
        'date.before_or_equal' => 'La date ne peut pas être future.',
        'heure_arrivee.required_if' => "L'heure d'arrivée est obligatoire si non absent.",
        'minutes_retard.required_if' => 'Veuillez renseigner le nombre de minutes de retard.',
        'minutes_retard.max' => 'Le retard maximum autorisé est de 300 minutes (5h).',
        'heure_depart.after' => "L'heure de départ doit être postérieure à l'arrivée.",
        'heure_depart.required_with' => "L'heure de départ est requise quand l'heure d'arrivée est renseignée."
    ]);

    // Vérification finale avant création
    if (Presence::where('user_id', $validated['user_id'])
               ->whereDate('date', $validated['date'])
               ->exists()) {
        return back()
            ->withErrors(['user_id' => 'Une présence existe déjà pour cet étudiant aujourd\'hui.'])
            ->withInput();
    }

    // Création de la présence
    Presence::create([
        'user_id' => $validated['user_id'],
        'date' => $validated['date'],
        'arrival_time' => $validated['absent'] ? null : $validated['heure_arrivee'],
        'departure_time' => $validated['absent'] ? null : $validated['heure_depart'],
        'late_minutes' => $validated['absent'] ? null : $validated['minutes_retard'],
        'absent' => $validated['absent'],
        'late' => $validated['absent'] ? false : $validated['en_retard'],
    ]);

    return redirect()->route('presences')
        ->with('success', 'Présence enregistrée avec succès.');
}

    
    public function edit($id)
{
    $presence = Presence::findOrFail($id);
    $users = User::orderBy('name')->get(['id', 'name', 'email']);

    return Inertia::render('admin/Presence/PresenceEdit', [
        'presence' => $presence,
        'users' => $users,
    ]);
}

}