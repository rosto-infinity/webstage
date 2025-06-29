<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function superadmin(Request $request)
    {
        $date = $request->input('date') ?? now()->toDateString();
        $selectedDate = Carbon::parse($date);

        // Présence hebdomadaire (lundi au dimanche)
        $startOfWeek = $selectedDate->startOfWeek()->toDateString();
        $endOfWeek = $selectedDate->endOfWeek()->toDateString();
        $weeklyPresence = [];
        for ($d = $startOfWeek; $d <= $endOfWeek; $d = Carbon::parse($d)->addDay()->toDateString()) {
            $presence = Presence::whereDate('date', $d)->get();
            $present = $presence->where('absent', false)->count();
            $absent = $presence->where('absent', true)->count();
            $weeklyPresence[] = [
                'day' => Carbon::parse($d)->format('D'),
                'present' => $present,
                'absent' => $absent,
            ];
        }

        // Tendance mensuelle (6 derniers mois)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = $selectedDate->copy()->subMonths($i);
            $month = $monthDate->format('M');
            $presencesInMonth = Presence::whereYear('date', $monthDate->year)
                ->whereMonth('date', $monthDate->month)
                ->get();
            $totalInMonth = $presencesInMonth->count();
            $presentInMonth = $presencesInMonth->where('absent', false)->count();
            $rate = $totalInMonth > 0 ? round(($presentInMonth / $totalInMonth) * 100, 2) : 0;
            $monthlyTrend[] = [
                'month' => $month,
                'rate' => $rate,
            ];
        }

        // Raisons d'absence pour le mois
        $monthStart = $selectedDate->startOfMonth()->toDateString();
        $monthEnd = $selectedDate->endOfMonth()->toDateString();
        $absenceReasons = Presence::whereBetween('date', [$monthStart, $monthEnd])
            ->where('absent', true)
            ->join('absence_reasons', 'presences.absence_reason_id', '=', 'absence_reasons.id')
            ->select('absence_reasons.name as reason', DB::raw('count(*) as count'))
            ->groupBy('absence_reasons.name')
            ->get()
            ->map(function ($item) {
                $color = match ($item->reason) {
                    'Maladie' => '#b6b2ff',
                    'Transport' => '#EF4444',
                    'Familial' => '#654bc3',
                    'Autre' => '#64748B',
                    default => '#ccc',
                };

                return [
                    'label' => $item->reason,
                    'value' => $item->count,
                    'color' => $color,
                ];
            })
            ->toArray();

        // Statistiques quotidiennes
        $baseQuery = Presence::whereDate('date', $date);

        return Inertia::render('SuperAdmin/Dashboard', [
            'totalUsers' => User::count(),
            'presenceCount' => (clone $baseQuery)->count(),
            'Countpresent' => (clone $baseQuery)->where('absent', false)->count(),
            'Countabsent' => (clone $baseQuery)->where('absent', true)->count(),
            'Countlate' => (clone $baseQuery)->where('late', true)->count(),
            'selectedDate' => $date,
            'weeklyPresence' => $weeklyPresence,
            'monthlyTrend' => $monthlyTrend,
            'absenceReasons' => $absenceReasons,
        ]);
    }

    public function admin(Request $request)
    {
        return Inertia::render('Admin/Dashboard', []);
    }
}
