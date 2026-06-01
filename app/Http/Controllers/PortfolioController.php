<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\MediaLibrary;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function edit()
    {
        $portfolio = Portfolio::first() ?? new Portfolio();
        $media = MediaLibrary::latest()->get();
        $items = PortfolioItem::all()->groupBy('type');
        
        return view('portfolio.edit', compact('portfolio', 'media', 'items'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'profile_picture_id' => 'nullable|exists:media_libraries,id'
        ]);

        Portfolio::updateOrCreate(['id' => 1], $request->except('_token'));
        return redirect()->back()->with('success', 'Portfolio information updated successfully!');
    }
}