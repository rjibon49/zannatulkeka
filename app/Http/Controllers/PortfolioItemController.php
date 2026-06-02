<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PortfolioItemController extends Controller
{
    public function store(Request $request)
    {
        $portfolio = Portfolio::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Zannatul Keka',
                'status' => 'active',
            ]
        );

        $validated = $request->validate([
            'portfolio_id' => ['nullable', 'exists:portfolios,id'],
            'type' => ['required', Rule::in(PortfolioItem::TYPES)],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'period' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:255'],
            'media_library_id' => ['nullable', 'exists:media_libraries,id'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        PortfolioItem::create([
            'portfolio_id' => $validated['portfolio_id'] ?? $portfolio->id,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'organization_name' => $validated['organization_name'] ?? null,
            'location' => $validated['location'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'period' => $validated['period'] ?? null,
            'url' => $validated['url'] ?? null,
            'media_library_id' => $validated['media_library_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_featured' => $request->boolean('is_featured'),
            'status' => $validated['status'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Portfolio item added successfully.');
    }

    public function update(Request $request, PortfolioItem $portfolioItem)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(PortfolioItem::TYPES)],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'period' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:255'],
            'media_library_id' => ['nullable', 'exists:media_libraries,id'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $portfolioItem->update([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'organization_name' => $validated['organization_name'] ?? null,
            'location' => $validated['location'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'period' => $validated['period'] ?? null,
            'url' => $validated['url'] ?? null,
            'media_library_id' => $validated['media_library_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_featured' => $request->boolean('is_featured'),
            'status' => $validated['status'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Portfolio item updated successfully.');
    }

    public function destroy(PortfolioItem $portfolioItem)
    {
        $portfolioItem->delete();

        return redirect()
            ->back()
            ->with('success', 'Portfolio item deleted successfully.');
    }
}