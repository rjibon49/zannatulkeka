<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\MediaLibrary;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function edit()
    {
        // ডাটাবেসে ১ নম্বর আইডি থাকলে আনবে, না থাকলে খালি অবজেক্ট তৈরি করবে
        $portfolio = Portfolio::find(1) ?? new Portfolio();
        // মোডালের জন্য মিডিয়া লাইব্রেরির সব ছবি আনা হলো
        $media = MediaLibrary::latest()->get();
        
        return view('portfolio.edit', compact('portfolio', 'media'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'profile_picture_id' => 'nullable|exists:media_libraries,id'
        ]);

        // সবসময় ১ নম্বর আইডিতেই ডাটা সেভ বা আপডেট হবে (Single User)
        Portfolio::updateOrCreate(
            ['id' => 1],
            $request->except('_token')
        );

        return redirect()->back()->with('success', 'Portfolio information updated successfully!');
    }
}