<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function superadmin(Request $request)
    {
        // -Validation des paramètres reçus
        $validated = $request->validate([
            'date' => 'nullable|date_format:Y-m-d',
            'month' => 'nullable|date_format:Y-m',
            'week' => 'nullable|date_format:Y-m-d',
            'user' => 'nullable|string|exists:users,name',
            'filterType' => 'nullable|in:day,week,month',
        ]);

        $date = $validated['date'] ?? now()->toDateString();
        $month = $validated['month'] ?? now()->format('Y-m');
        $week = $validated['week'] ?? now()->toDateString();
        $user = $validated['user'] ?? '';
        $filterType = $validated['filterType'] ?? 'day';

        //- Déterminer la plage de dates en fonction du type de filtre
        $startDate = match ($filterType) {
            'month' => Carbon::parse($month)->startOfMonth()->toDateString(),
            'week' => Carbon::parse($week)->startOfWeek()->toDateString(),
            default => $date, // day
        };
        $endDate = match ($filterType) {
            'month' => Carbon::parse($month)->endOfMonth()->toDateString(),
            'week' => Carbon::parse($week)->endOfWeek()->toDateString(),
            default => $date, // day
        };

        // ---Liste des utilisateurs
        $users = User::pluck('name')->toArray();

        // -Requête de base avec filtre utilisateur
        $query = Presence::query()->with('absenceReason');
        if ($user) {
            $query->whereHas('user', fn ($q) => $q->where('name', $user));
        }

        // -cStatistiques pour la période sélectionnée
        $presenceCount = $query->clone()->whereBetween('date', [$startDate, $endDate])->count();
        $countPresent = $query->clone()->whereBetween('date', [$startDate, $endDate])
            ->where('absent', false)->count();
        $countAbsent = $query->clone()->whereBetween('date', [$startDate, $endDate])
            ->where('absent', true)->count();
        $countLate = $query->clone()->whereBetween('date', [$startDate, $endDate])
            ->where('late', true)->count();

        return Inertia::render('SuperAdmin/Dashboard', [
            'totalUsers' => $user ? 1 : User::count(),
            'presenceCount' => $presenceCount,
            'Countpresent' => $countPresent,
            'Countabsent' => $countAbsent,
            'Countlate' => $countLate,
            'selectedDate' => $date,
            'selectedMonth' => $month,
            'selectedWeek' => $week,
            'selectedUser' => $user,
            'users' => $users,
            'filterType' => $filterType,
            'weeklyPresence' => $this->getWeeklyStats($week, $user),
            'monthlyTrend' => $this->getMonthlyStats($month, $user),
            'absenceReasons' => $this->getAbsenceReasons($month, $user),
        ]);
    }

    /**
     * --Récupère les statistiques hebdomadaires pour la semaine donnée.
     */
    private function getWeeklyStats(string $week, string $user = ''): array
    {
        $startOfWeek = Carbon::parse($week)->startOfWeek();
        $query = Presence::query();
        if ($user) {
            $query->whereHas('user', fn ($q) => $q->where('name', $user));
        }

        return collect(range(0, 6))->map(function ($day) use ($startOfWeek, $query) {
            $currentDate = $startOfWeek->copy()->addDays($day);
            return [
                'day' => $currentDate->isoFormat('ddd'),
                'present' => $query->clone()->whereDate('date', $currentDate)
                    ->where('absent', false)->count(),
                'absent' => $query->clone()->whereDate('date', $currentDate)
                    ->where('absent', true)->count(),
            ];
        })->toArray();
    }

    /**
     * --cRécupère les tendances mensuelles pour le mois donné.
     */
    private function getMonthlyStats(string $month, string $user = ''): array
    {
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();
        $query = Presence::query();
        if ($user) {
            $query->whereHas('user', fn ($q) => $q->where('name', $user));
        }

        $totalUsers = $user ? 1 : User::count();
        return $query->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->selectRaw('DAY(date) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(fn ($item) => [
                'day' => (string) $item->day,
                'rate' => $totalUsers > 0 ? ($item->count / $totalUsers) * 100 : 0,
            ])->toArray();
    }

    /**
     * Récupère les motifs d'absence pour le mois donné.
     */
    private function getAbsenceReasons(string $month, string $user = ''): array
    {
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();
        $query = Presence::query()->where('absent', true)->with('absenceReason');
        if ($user) {
            $query->whereHas('user', fn ($q) => $q->where('name', $user));
        }

        return $query->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(fn ($presence) => $presence->absenceReason ? $presence->absenceReason->name : null)
            ->map(fn ($group, $reason) => [
                'label' => $reason ?? 'Sans motif',
                'value' => $group->count(),
                'color' => $reason ? sprintf('#%06X', mt_rand(0, 0xFFFFFF)) : '#EF4444',
            ])->values()->toArray();
    }

    /**
     * Tableau de bord pour les administrateurs.
     */
    public function admin(Request $request)
    {
        return Inertia::render('Admin/Dashboard', []);
    }
}