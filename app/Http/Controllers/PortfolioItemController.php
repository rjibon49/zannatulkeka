<?php

namespace App\Http\Controllers;

use App\Models\PortfolioItem;
use Illuminate\Http\Request;

class PortfolioItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'period' => 'nullable|string|max:255',
            'url' => 'nullable|url',
            'description' => 'nullable|string'
        ]);

        PortfolioItem::create($request->all());
        return back()->with('success', 'Item added successfully!');
    }

    public function destroy(PortfolioItem $portfolioItem)
    {
        $portfolioItem->delete();
        return back()->with('success', 'Item deleted successfully!');
    }
}