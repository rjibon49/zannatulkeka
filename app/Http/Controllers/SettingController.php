<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\MediaLibrary;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first() ?? new Setting();
        $media = MediaLibrary::latest()->get();
        return view('settings.index', compact('setting', 'media'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'subheading' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'banner_media_id' => 'nullable|exists:media_libraries,id'
        ]);

        Setting::updateOrCreate(['id' => 1], $request->except('_token'));
        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}