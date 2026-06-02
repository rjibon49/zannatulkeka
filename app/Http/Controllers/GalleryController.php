<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\MediaLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $galleries = Gallery::with(['coverImage', 'images.media', 'article'])
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('galleries.index', compact('galleries', 'search', 'status'));
    }

    public function create()
    {
        $media = MediaLibrary::where('type', 'image')
            ->latest()
            ->get();

        return view('galleries.create', compact('media'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:galleries,slug'],
            'description' => ['nullable', 'string'],
            'cover_media_id' => ['nullable', 'exists:media_libraries,id'],
            'article_id' => ['nullable', 'exists:articles,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'media_library_ids' => ['nullable', 'array'],
            'media_library_ids.*' => ['exists:media_libraries,id'],
        ]);

        $gallery = Gallery::create([
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['slug'] ?? $validated['title']),
            'description' => $validated['description'] ?? null,
            'cover_media_id' => $validated['cover_media_id'] ?? null,
            'article_id' => $validated['article_id'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        $this->syncGalleryImages($gallery, $validated['media_library_ids'] ?? []);

        return redirect()
            ->route('galleries.index')
            ->with('success', 'Gallery created successfully.');
    }

    public function edit(Gallery $gallery)
    {
        $gallery->load(['images.media', 'coverImage']);

        $media = MediaLibrary::where('type', 'image')
            ->latest()
            ->get();

        return view('galleries.edit', compact('gallery', 'media'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('galleries', 'slug')->ignore($gallery->id),
            ],
            'description' => ['nullable', 'string'],
            'cover_media_id' => ['nullable', 'exists:media_libraries,id'],
            'article_id' => ['nullable', 'exists:articles,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'media_library_ids' => ['nullable', 'array'],
            'media_library_ids.*' => ['exists:media_libraries,id'],
        ]);

        $gallery->update([
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['slug'] ?? $validated['title'], $gallery->id),
            'description' => $validated['description'] ?? null,
            'cover_media_id' => $validated['cover_media_id'] ?? null,
            'article_id' => $validated['article_id'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        if ($request->has('media_library_ids')) {
            $this->syncGalleryImages($gallery, $validated['media_library_ids'] ?? []);
        }

        return redirect()
            ->route('galleries.index')
            ->with('success', 'Gallery updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();

        return redirect()
            ->route('galleries.index')
            ->with('success', 'Gallery deleted successfully.');
    }

    private function syncGalleryImages(Gallery $gallery, array $mediaIds): void
    {
        $gallery->images()->delete();

        foreach (array_values($mediaIds) as $index => $mediaId) {
            $gallery->images()->create([
                'media_library_id' => $mediaId,
                'sort_order' => $index,
                'status' => 'active',
            ]);
        }
    }

    private function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $counter = 2;

        while (
            Gallery::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}