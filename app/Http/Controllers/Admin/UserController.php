<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('admin/UserIndex', [
            'users' => User::query()->paginate(3),
            'totalUsers' => User::count(), // Ajout du nombre total d'utilisateurs
        ]);
    }
}
