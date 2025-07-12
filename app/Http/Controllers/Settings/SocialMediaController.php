<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SocialMediaController extends Controller
{
    public function index()
    {
        return Inertia::render('settings/Media', [
            'socialMedias' => auth()->user()->socialMedias,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform' => [
                'required',
                'in:github,twitter,linkedin,facebook,instagram,youtube,other',
                Rule::unique('social_media')->where(function ($query) use ($request) {
                    return $query->where('user_id', auth()->id())
                        ->where('platform', $request->platform);
                }),
            ],
            'url' => 'required|url|starts_with:https://'.$request->platform.'.com',
            'display_name' => 'nullable|string|max:255',
        ], [
            'platform.unique' => 'Vous avez déjà ajouté ce réseau social',
            'url.starts_with' => 'L\'URL doit correspondre au réseau social sélectionné',
        ]);

        auth()->user()->socialMedias()->create($request->only([
            'platform', 'url', 'display_name',
        ]));

        return back()->with('success', 'Réseau social ajouté avec succès');
    }

    public function update(Request $request, SocialMedia $socialMedia)
    {
        $platformDomains = [
            'github' => 'github.com',
            'twitter' => 'twitter.com',
            'linkedin' => 'linkedin.com',
            'facebook' => 'facebook.com',
            'instagram' => 'instagram.com',
            'youtube' => 'youtube.com',
        ];

        $request->validate([
            'platform' => [
                'required',
                'in:github,twitter,linkedin,facebook,instagram,youtube,other',
                Rule::unique('social_media')->where(function ($query) use ($request, $socialMedia) {
                    return $query->where('user_id', auth()->id())
                        ->where('platform', $request->platform)
                        ->where('id', '!=', $socialMedia->id);
                }),
            ],
            'url' => [
                'required',
                'url',
                function ($attribute, $value, $fail) use ($request, $platformDomains) {
                    if ($request->platform !== 'other' &&
                        ! str_contains($value, $platformDomains[$request->platform])) {
                        $fail("L'URL doit appartenir au domaine ".$platformDomains[$request->platform]);
                    }
                },
            ],
            'display_name' => 'nullable|string|max:255',
        ]);

        $socialMedia->update($request->only(['platform', 'url', 'display_name']));

        return back()->with('success', 'Réseau social mis à jour avec succès');
    }

    public function destroy(SocialMedia $socialMedia)
    {
        $this->authorize('delete', $socialMedia);
        $socialMedia->delete();

        return back()->with('success', 'Media deleted successfully');
    }
}
