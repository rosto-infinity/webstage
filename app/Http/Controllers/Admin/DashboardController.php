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
    public function index(Request $request)
    {
        $date = $request->input('date') ?? now()->toDateString();

        $baseQuery = Presence::whereDate('date', $date);

        return Inertia::render('admin/Dashboard', [
            'totalUsers'    => User::count(),
            'presenceCount' => (clone $baseQuery)->count(),
            'Countpresent'  => (clone $baseQuery)->where('absent', false)->count(),
            'Countabsent'   => (clone $baseQuery)->where('absent', true)->count(),
            'Countlate'     => (clone $baseQuery)->where('late', true)->count(),
            'selectedDate'  => $date,
        ]);
    }
}
