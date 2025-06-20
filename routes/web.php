<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Presence;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PresenceController;

Route::get('/', function () {
    $totalUsers =User::count();
    return Inertia::render('Welcome',compact('totalUsers'));
})->name('home');

Route::middleware(['auth'])->name('users.')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('index');
    Route::get('users/create', [UserController::class, 'create'])->name('create');
    Route::post('users', [UserController::class, 'store'])->name('store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('destroy');
});


Route::prefix('presences')->group(function () {
    Route::get('/users', [PresenceController::class, 'index'])->name('presences');
    
    Route::get('/add', [PresenceController::class, 'add'])->name('presences.add');
    Route::post('/store', [PresenceController::class, 'store'])->name('presences.store');
    
    Route::get('/{id}/edit', [PresenceController::class, 'edit'])->name('presences.edit');
    Route::put('/{id}', [PresenceController::class, 'update'])->name('presences.update');
    Route::delete('/{presence}', [PresenceController::class, 'destroy'])
    ->name('presences.destroy');
});
Route::get('dashboard', [DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/users', [UserController::class, 'index'])->name('presences.users');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

 Route::get('/{any}', function () {
       return Inertia::render('NotFoundPage');
     })->where('any', '.*')->name('notfound');

