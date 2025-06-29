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
        // Validation de la date (format Y-m-d)
        $validated = $request->validate([
            'date' => 'nullable|date_format:Y-m-d',
        ]);

        $date = $validated['date'] ?? now()->toDateString();

        // Requêtes optimisées avec jointures si nécessaire
        return Inertia::render('SuperAdmin/Dashboard', [
            'totalUsers' => User::count(),
            'presenceCount' => Presence::whereDate('date', $date)->count(),
            'Countpresent' => Presence::whereDate('date', $date)->where('absent', false)->count(),
            'Countabsent' => Presence::whereDate('date', $date)->where('absent', true)->count(),
            'Countlate' => Presence::whereDate('date', $date)->where('late', true)->count(),
            'selectedDate' => $date,
            // Ajout des données dynamiques pour les graphiques
            'dailyPresence' => $this->getWeeklyStats($date),
            'monthlyTrend' => $this->getMonthlyStats($date),
        ]);
    }

    private function getWeeklyStats(string $date): array
    {
        $startOfWeek = Carbon::parse($date)->startOfWeek();

        return collect(range(0, 4))->map(function ($day) use ($startOfWeek) {
            $currentDate = $startOfWeek->copy()->addDays($day);

            return [
                'day' => $currentDate->isoFormat('ddd'),
                'present' => Presence::whereDate('date', $currentDate)->where('absent', false)->count(),
                'absent' => Presence::whereDate('date', $currentDate)->where('absent', true)->count(),
            ];
        })->toArray();
    }

    private function getMonthlyStats(string $date): array
    {
        $month = Carbon::parse($date)->month;

        return Presence::query()
            ->whereMonth('date', $month)
            ->selectRaw('MONTH(date) as month, DAY(date) as day, COUNT(*) as count')
            ->groupBy('month', 'day')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::create()->month($item->month)->isoFormat('MMM'),
                    'rate' => ($item->count / User::count()) * 100,
                ];
            })
            ->toArray();
    }

    public function admin(Request $request)
    {
        return Inertia::render('Admin/Dashboard', []);
    }
}
