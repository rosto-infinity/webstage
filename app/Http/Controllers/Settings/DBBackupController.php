<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DBBackupController extends Controller
{
    /**
     * Affiche la liste des sauvegardes
     */
    public function index()
    {
        $backups = collect(Storage::files('private/backup'))
            ->filter(fn ($file) => Str::endsWith($file, '.gz'))
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'size' => Storage::size($file),
                    'last_modified' => Storage::lastModified($file),
                    'path' => $file,
                ];
            })
            ->sortByDesc('last_modified')
            ->values();

        return Inertia::render('settings/DbBackup', [
            'backups' => $backups,
        ]);
    }

    /**
     * Télécharge une sauvegarde spécifique
     */
    public function download(Request $request): StreamedResponse
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        if (! Storage::exists($request->path)) {
            abort(404);
        }

        return Storage::download($request->path);
    }

    /**
     * Crée une nouvelle sauvegarde
     */
    public function create()
    {
        Artisan::call('app:dbbackup');

        return redirect()->back()
            ->with('success', 'La sauvegarde a été lancée avec succès');
    }
}
