<?php

namespace App\Http\Controllers;

use App\Models\MediaLibrary;
use App\Models\Portfolio;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function edit()
    {
        $portfolio = Portfolio::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Zannatul Keka',
                'status' => 'active',
            ]
        );

        $media = MediaLibrary::latest()->get();

        $items = PortfolioItem::where('portfolio_id', $portfolio->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy('type');

        return view('portfolio.edit', compact('portfolio', 'media', 'items'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'short_intro' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],

            'profile_picture_id' => ['nullable', 'exists:media_libraries,id'],
            'cover_media_id' => ['nullable', 'exists:media_libraries,id'],
            'resume_pdf_id' => ['nullable', 'exists:media_libraries,id'],

            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],

            'website_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],

            'status' => ['nullable', 'in:active,inactive'],
        ]);

        Portfolio::updateOrCreate(
            ['id' => 1],
            array_merge($validated, [
                'status' => $validated['status'] ?? 'active',
            ])
        );

        return redirect()
            ->back()
            ->with('success', 'Portfolio information updated successfully.');
    }
}