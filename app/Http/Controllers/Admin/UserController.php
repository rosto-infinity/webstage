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
    public function indexlist(Request $request)
    {
        return Inertia::render('SuperAdmin/Users/UserIndex', [
            // 'users' => User::query()->paginate(7),
            'users' => User::latest()->paginate(7),
            'totalUsers' => User::count(), // Ajout du nombre total d'utilisateurs
        ]);
    }
     public function index()
    {
        $user = Auth::user();

        $total = Presence::where('user_id', $user->id)->count();
        
        $present = Presence::where('user_id', $user->id)->where('absent', false)->count();
        $absent = Presence::where('user_id', $user->id)->where('absent', true)->count();
        $late = Presence::where('user_id', $user->id)->where('late', true)->count();
        $lateMinutes = Presence::where('user_id', $user->id)->sum('late_minutes');

        // Statistiques hebdo (présent/absent par jour)
        $weekDays = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weekStats = [];
        foreach ($weekDays as $day) {
            $weekStats[$day] = ['present' => 0, 'absent' => 0];
        }
        $weekData = Presence::where('user_id', $user->id)
            ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->date)->format('D');
            });
        foreach ($weekData as $day => $items) {
            $weekStats[$day]['present'] = $items->where('absent', false)->count();
            $weekStats[$day]['absent'] = $items->where('absent', true)->count();
        }

        // Statistiques mensuelles (taux de présence par mois)
        $monthlyStats = Presence::where('user_id', $user->id)
            ->selectRaw('MONTH(date) as month, COUNT(*) as total, SUM(CASE WHEN absent = 0 THEN 1 ELSE 0 END) as presents')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($row) {
                return [
                    'month' => Carbon::create()->month($row->month)->format('M'),
                    'rate' => $row->total > 0 ? round($row->presents / $row->total * 100, 1) : 0,
                ];
            })->toArray();

        return Inertia::render('Dashboard', [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'lateMinutes' => $lateMinutes,
            'weekStats' => $weekStats,
            'monthlyStats' => $monthlyStats,
            'isSuperAdmin' => $user->isSuperAdmin(),
        ]);
    }

    public function create()
    {
        return Inertia::render('SuperAdmin/Users/UserCreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        return redirect()->route('users.index')->with('success', 'Utilisateur créé');
    }

    public function edit(User $user)
    {
        return Inertia::render('SuperAdmin/Users/UserEdit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|confirmed|min:8',
        ]);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();
        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé');
    }
}
