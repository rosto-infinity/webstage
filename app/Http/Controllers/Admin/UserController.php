<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('admin/UserIndex', [
            'users' => User::query()->paginate(6),
            'totalUsers' => User::count() // Ajout du nombre total d'utilisateurs
        ]);
    }
}
