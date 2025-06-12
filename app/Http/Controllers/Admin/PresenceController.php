<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PresenceController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Presence/PresenceIndex');
    }
    public function add()
    {
        return Inertia::render('admin/Presence/PresenceAdd');
    }
     public function store(Request $request)
    {
        $data = $request->validate([
            'nom_prenom'     => 'required|string|max:255',
            'email'          => 'required|email',
            'date'           => 'required|date',
            'heure_arrivee'  => 'nullable|date_format:H:i',
            'heure_depart'   => 'nullable|date_format:H:i',
            'minutes_retard' => 'nullable|integer|min:0',
            'absent'         => 'required|boolean',
            'en_retard'      => 'required|boolean',
        ]);

        // Logique de sauvegarde via modèle Presence (à créer)
        // Presence::create($data);

        return redirect()->route('presence.create')
                         ->with('success', 'Présence ajoutée avec succès');
    }
}
