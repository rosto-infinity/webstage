<?php

use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::prefix('presences')->group(function () {
    Route::get('/users', [PresenceController::class, 'index'])->name('presences');
    Route::get('/add', [PresenceController::class, 'add'])->name('presences.add');
    Route::post('/store', [PresenceController::class, 'store'])->name('presences.store');
    
    Route::get('/{id}/edit', [PresenceController::class, 'edit'])->name('presences.edit');
    Route::put('/{id}', [PresenceController::class, 'update'])->name('presences.update');
});
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/users', [UserController::class, 'index'])->name('presences.users');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

 Route::get('/{any}', function () {
       return Inertia::render('NotFoundPage');
     })->where('any', '.*')->name('notfound');
  
