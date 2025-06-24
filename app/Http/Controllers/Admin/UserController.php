<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Affiche la liste paginée des utilisateurs (pour les super admins)
     * 
     * @param Request $request Requête HTTP
     * @return \Inertia\Response Vue Inertia avec la liste des utilisateurs
     */
    public function indexlist(Request $request)
    {
        return Inertia::render('SuperAdmin/Users/UserIndex', [
            // -Récupère les utilisateurs triés par date de création (les plus récents en premier)
            'users' => User::latest()->paginate(7),
            // -Compte le nombre total d'utilisateurs pour affichage statistique
            'totalUsers' => User::count(),
        ]);
    }

    /**
     * -Affiche le tableau de bord utilisateur avec les statistiques de présence
     * 
     * @return \Inertia\Response Vue Inertia avec toutes les données statistiques
     */
    public function index()
    {
        // -Récupère l'utilisateur actuellement authentifié
        $user = Auth::user();

        // -STATISTIQUES GLOBALES
        // -Compte le nombre total de présences
        $total = Presence::where('user_id', $user->id)->count();
        
        // -Compte les présences, absences et retards
        $present = Presence::where('user_id', $user->id)->where('absent', false)->count();
        $absent = Presence::where('user_id', $user->id)->where('absent', true)->count();
        $late = Presence::where('user_id', $user->id)->where('late', true)->count();
        $lateMinutes = Presence::where('user_id', $user->id)->sum('late_minutes');

        // -STATISTIQUES HEBDOMADAIRES
        // Initialise un tableau pour les 7 jours de la semaine
        $weekDays = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weekStats = [];
        
        // - -Initialise chaque jour avec des compteurs à 0
        foreach ($weekDays as $day) {
            $weekStats[$day] = ['present' => 0, 'absent' => 0];
        }
        
        // -Récupère les données de la semaine en cours
        $weekData = Presence::where('user_id', $user->id)
            ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
            ->get()
            ->groupBy(function($item) {
                // -Groupe par jour de la semaine (format 'Mon', 'Tue', etc.)
                return Carbon::parse($item->date)->format('D');
            });
        
        // --Met à jour les compteurs pour chaque jour ayant des données
        foreach ($weekData as $day => $items) {
            $weekStats[$day]['present'] = $items->where('absent', false)->count();
            $weekStats[$day]['absent'] = $items->where('absent', true)->count();
        }

        // -STATISTIQUES MENSUELLES
        $monthlyStats = Presence::where('user_id', $user->id)
            // -Sélectionne le mois, le total et le nombre de présences
            ->selectRaw('MONTH(date) as month, COUNT(*) as total, SUM(CASE WHEN absent = 0 THEN 1 ELSE 0 END) as presents')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($row) {
                // -- Formate les données pour l'affichage
                return [
                    'month' => Carbon::create()->month($row->month)->format('M'), // Format 'Jan', 'Feb', etc.
                    'rate' => $row->total > 0 ? round($row->presents / $row->total * 100, 1) : 0, // Taux de présence en %
                ];
            })->toArray();

        // ---Renvoie toutes les données à la vue Inertia
        return Inertia::render('Dashboard', [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'lateMinutes' => $lateMinutes,
            'weekStats' => $weekStats,
            'monthlyStats' => $monthlyStats,
            'isSuperAdmin' => $user->isSuperAdmin(), // Vérifie si l'utilisateur est un super admin
        ]);
    }

    /**
     * -Affiche le formulaire de création d'un utilisateur
     * 
     * @return \Inertia\Response Vue Inertia avec le formulaire
     */
    public function create()
    {
        return Inertia::render('SuperAdmin/Users/UserCreate');
    }

    /**
     * -Enregistre un nouvel utilisateur en base de données
     * 
     * @param Request $request Requête HTTP contenant les données du formulaire
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste des utilisateurs
     */
    public function store(Request $request)
    {
        // -Validation des données du formulaire
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);
        
        // -Création de l'utilisateur avec le mot de passe hashé
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        
        // -Redirection avec message de succès
        return redirect()->route('users.index')->with('success', 'Utilisateur créé');
    }

    /**
     * --Affiche le formulaire d'édition d'un utilisateur
     * 
     * @param User $user L'utilisateur à éditer
     * @return \Inertia\Response Vue Inertia avec le formulaire pré-rempli
     */
    public function edit(User $user)
    {
        return Inertia::render('SuperAdmin/Users/UserEdit', [
            'user' => $user // Passe l'utilisateur à éditer à la vue
        ]);
    }

    /**
     * Met à jour un utilisateur existant
     * 
     * @param Request $request Requête HTTP contenant les données mises à jour
     * @param User $user L'utilisateur à mettre à jour
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste des utilisateurs
     */
    public function update(Request $request, User $user)
    {
        // Validation des données (le mot de passe est optionnel)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id, // Ignore l'email actuel de l'utilisateur
            'password' => 'nullable|confirmed|min:8',
        ]);
        
        // Mise à jour des données de base
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Mise à jour du mot de passe seulement si fourni
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour');
    }

    /**
     * Supprime un utilisateur
     * 
     * @param User $user L'utilisateur à supprimer
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste des utilisateurs
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé');
    }
}
