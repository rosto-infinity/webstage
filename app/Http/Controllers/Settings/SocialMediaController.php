<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SocialMediaController extends Controller
{
    public function index()
    {
        return Inertia::render('settings/Media', [
            'socialMedias' => auth()->user()->socialMedias
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform' => 'required|in:github,twitter,linkedin,facebook,instagram,youtube,other',
            'url' => 'required|url',
            'display_name' => 'nullable|string|max:255'
        ]);

        auth()->user()->socialMedias()->create($request->only([
            'platform', 'url', 'display_name'
        ]));

        return back()->with('success', 'Media added successfully');
    }

    public function update(Request $request, SocialMedia $socialMedia)
    {
        $this->authorize('update', $socialMedia);

        $request->validate([
            'platform' => 'required|in:github,twitter,linkedin,facebook,instagram,youtube,other',
            'url' => 'required|url',
            'display_name' => 'nullable|string|max:255'
        ]);

        $socialMedia->update($request->only([
            'platform', 'url', 'display_name'
        ]));

        return back()->with('success', 'Media updated successfully');
    }

    public function destroy(SocialMedia $socialMedia)
    {
        $this->authorize('delete', $socialMedia);
        $socialMedia->delete();
        return back()->with('success', 'Media deleted successfully');
    }
}
