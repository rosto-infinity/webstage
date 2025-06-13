<?php

use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/presences/users',[PresenceController::class,'index'])->name('presences');
Route::get('/presences/add',[PresenceController::class,'add'])->name('presences.add');
Route::post('/presences/store', [PresenceController::class, 'store'])->name('presences.store');
Route::get('/users',[UserController::class,'index'])->name('presences.users');



Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//  Route::get('/{any}', function () {
//     return Inertia::render('NotFoundPage');
//  })->where('any', '.*');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
