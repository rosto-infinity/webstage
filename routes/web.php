<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $totalUsers = User::count();

    return Inertia::render('Welcome', compact('totalUsers'));
})->name('home');

Route::get('dashboard', [UserController::class, 'index'])->middleware(['auth', 'verified', 'prevent-back'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::prefix('gestions')->name('users.')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('index');
        Route::get('users/create', [UserController::class, 'create'])->name('create');
        Route::post('users', [UserController::class, 'store'])->name('store');
    });
});

Route::middleware(['auth', 'verified', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [DashboardController::class, 'superadmin'])->name('dashboard.superadmin');
    Route::prefix('gestions')->name('users.')->group(function () {
        Route::get('users', [UserController::class, 'indexlist'])->name('index');
        Route::get('users/create', [UserController::class, 'create'])->name('create');
        Route::post('users', [UserController::class, 'store'])->name('store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('presences')->group(function () {
        Route::get('/excel', [PresenceController::class, 'excel'])->name('presences.excel');
        Route::get('/download-all', [PresenceController::class, 'downloadAll'])->name('presences.downloadAll');
        Route::get('/users', [PresenceController::class, 'index'])->name('presences');
        Route::get('/add', [PresenceController::class, 'add'])->name('presences.add');
        Route::post('/store', [PresenceController::class, 'store'])->name('presences.store');
        Route::get('/{id}/edit', [PresenceController::class, 'edit'])->name('presences.edit');
        Route::put('/{id}', [PresenceController::class, 'update'])->name('presences.update');
        Route::delete('/{presence}', [PresenceController::class, 'destroy'])
            ->name('presences.destroy');
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::get('/{any}', function () {
    return Inertia::render('NotFoundPage');
})->where('any', '.*')->name('notfound');
