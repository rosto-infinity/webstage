<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        
        $presenceCount =Presence::count();
        $totalUsers =User::count();

            $Countpresent = Presence::whereDate('date', today())->where('absent', false)->count();
            $Countabsent = Presence::whereDate('date', today())->where('absent', true)->count();
            $Countlate =Presence::whereDate('date', today())->where('late', true)->count();
 var_dump($presenceCount);
        
     return Inertia::render('Dashboard', compact(
            'totalUsers',
            'presenceCount',
            'Countpresent',
            'Countabsent',
            'Countlate',
            
        ));

    //    $presences = Presence::with('user')
    //     ->orderBy('date', 'desc')
    //     ->get()
    //     ->map(fn ($p) => [
    //         'id' => $p->id,
    //         'date' => $p->date,
    //         'arrival_time' => $p->arrival_time,
    //         'departure_time' => $p->departure_time,
    //         'late_minutes' => $p->late_minutes,
    //         'absent' => $p->absent,
    //         'late' => $p->late,
    //         'user' => [
    //             'name' => $p->user->name,
    //             'email' => $p->user->email,
    //         ],
    //     ]);
        // Statistiques globales
        // $statss = [
        //     'total' => User::count(),
        //     'present' => Presence::whereDate('date', today())->where('absent', false)->count(),
        //     'absent' => Presence::whereDate('date', today())->where('absent', true)->count(),
        //     'late' => Presence::whereDate('date', today())->where('late', true)->count()
        // ];
        
        // var_dump($presences);
        // dd($statss);
        // Données hebdomadaires (derniers 5 jours ouvrés)
        // $dailyPresence = Presence::select(
        //         DB::raw('DAYNAME(date) as day'),
        //         DB::raw('SUM(CASE WHEN absent = 0 THEN 1 ELSE 0 END) as present'),
        //         DB::raw('SUM(CASE WHEN absent = 1 THEN 1 ELSE 0 END) as absent')
        //     )
        //     ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
        //     ->groupBy(DB::raw('DAYNAME(date)'), DB::raw('DAYOFWEEK(date)'))
        //     ->orderBy(DB::raw('DAYOFWEEK(date)'))
        //     ->get()
        //     ->map(function ($item) {
        //         return [
        //             'day' => substr($item->day, 0, 3), // Format court (Lun, Mar...)
        //             'present' => (int)$item->present,
        //             'absent' => (int)$item->absent
        //         ];
        //     });

        // // Tendance mensuelle (6 derniers mois)
        // $monthlyTrend = Presence::select(
        //         DB::raw("DATE_FORMAT(date, '%b') as month"),
        //         DB::raw("ROUND(100 * SUM(CASE WHEN absent = 0 THEN 1 ELSE 0 END) / COUNT(*), 0) as rate")
        //     )
        //     ->whereBetween('date', [now()->subMonths(6), now()])
        //     ->groupBy(DB::raw("DATE_FORMAT(date, '%Y-%m')"), DB::raw("DATE_FORMAT(date, '%b')"))
        //     ->orderBy(DB::raw("DATE_FORMAT(date, '%Y-%m')"))
        //     ->get();

        // // Motifs d'absence (exemple avec données simulées)
        // $absenceReasons = DB::table('absence_reasons')
        //     ->select('reason', DB::raw('COUNT(*) as count'))
        //     ->whereMonth('created_at', now()->month)
        //     ->groupBy('reason')
        //     ->get();

       
       
        //     return inertia('Dashboard', compact(
        //     'statss',
        //     'totalUsers',
        //     'presenceCount',
        //     'dailyPresence',
        //     'monthlyTrend',
        //     'absenceReasons'
        // ));
    }
}
