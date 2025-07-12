<?php

use App\Http\Controllers\Settings\DBBackupController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\SocialMediaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'prevent-back'])->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/media', [SocialMediaController::class, 'index'])->name('media');
    Route::post('settings/media', [SocialMediaController::class, 'store'])->name('media.store');
    Route::put('settings/media/{socialMedia}', [SocialMediaController::class, 'update'])->name('media.update');
    Route::delete('settings/media/{socialMedia}', [SocialMediaController::class, 'destroy'])->name('media.destroy');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance');

    Route::get('settings/dbbackup', [DBBackupController::class, 'index'])
        ->name('dbbackup');

    Route::get('settings/dbbackup/download', [DBBackupController::class, 'download'])
        ->name('dbbackup.download');

    Route::post('settings/dbbackup/create', [DBBackupController::class, 'create'])
        ->name('dbbackup.create');
});
