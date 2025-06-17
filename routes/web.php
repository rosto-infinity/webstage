<?php

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

Route::prefix('presences')->group(function () {
    Route::get('/users', [PresenceController::class, 'index'])->name('presences');
    Route::get('/add', [PresenceController::class, 'add'])->name('presences.add');
    Route::post('/store', [PresenceController::class, 'store'])->name('presences.store');
    
    Route::get('/{id}/edit', [PresenceController::class, 'edit'])->name('presences.edit');
    Route::put('/{id}', [PresenceController::class, 'update'])->name('presences.update');
    Route::delete('/{presence}', [PresenceController::class, 'destroy'])
    ->name('presences.destroy');
});
Route::get('dashboard', function () {
      $presenceCount =Presence::count();
        $totalUsers =User::count();

            $Countpresent = Presence::whereDate('date', today())->where('absent', false)->count();
            $Countabsent = Presence::whereDate('date', today())->where('absent', true)->count();
            $Countlate =Presence::whereDate('date', today())->where('late', true)->count();
//  var_dump($presenceCount);
     return Inertia::render('Dashboard', compact(
            'totalUsers',
            'presenceCount',
            'Countpresent',
            'Countabsent',
            'Countlate',
            
        ));
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/users', [UserController::class, 'index'])->name('presences.users');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

 Route::get('/{any}', function () {
       return Inertia::render('NotFoundPage');
     })->where('any', '.*')->name('notfound');
  
