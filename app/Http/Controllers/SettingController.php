<?php

namespace App\Http\Controllers;

use App\Models\MediaLibrary;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::with([
            'logo',
            'favicon',
            'banner',
            'defaultOgImage',
        ])->firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Zannatul Keka',
                'site_title' => 'Zannatul Keka Portfolio',
                'site_description' => 'Personal portfolio, articles, gallery and video archive.',
                'heading' => 'Zannatul Keka',
                'subheading' => 'Portfolio, Articles and Creative Works',
            ]
        );

        $media = MediaLibrary::query()
            ->where('type', 'image')
            ->latest()
            ->get();

        return view('settings.index', compact('setting', 'media'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_title' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string'],

            'logo_media_id' => ['nullable', 'exists:media_libraries,id'],
            'favicon_media_id' => ['nullable', 'exists:media_libraries,id'],
            'banner_media_id' => ['nullable', 'exists:media_libraries,id'],

            'heading' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:255'],

            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],

            'facebook_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],

            'default_meta_title' => ['nullable', 'string', 'max:255'],
            'default_meta_description' => ['nullable', 'string'],
            'default_og_media_id' => ['nullable', 'exists:media_libraries,id'],

            'footer_text' => ['nullable', 'string'],
        ]);

        Setting::updateOrCreate(['id' => 1], $validated);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}