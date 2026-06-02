<?php

namespace App\Http\Controllers;

use App\Models\MediaLibrary;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $videos = Video::with(['thumbnail', 'article'])
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('video_url', 'like', '%' . $search . '%');
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('videos.index', compact('videos', 'search', 'status'));
    }

    public function create()
    {
        $media = MediaLibrary::where('type', 'image')
            ->latest()
            ->get();

        return view('videos.create', compact('media'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'article_id' => ['nullable', 'exists:articles,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:videos,slug'],
            'description' => ['nullable', 'string'],
            'video_url' => ['required', 'url', 'max:255'],
            'thumbnail_media_id' => ['nullable', 'exists:media_libraries,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        Video::create([
            'article_id' => $validated['article_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['slug'] ?? $validated['title']),
            'description' => $validated['description'] ?? null,
            'video_url' => $validated['video_url'],
            'youtube_video_id' => $this->extractYoutubeId($validated['video_url']),
            'thumbnail_media_id' => $validated['thumbnail_media_id'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('videos.index')
            ->with('success', 'Video created successfully.');
    }

    public function edit(Video $video)
    {
        $media = MediaLibrary::where('type', 'image')
            ->latest()
            ->get();

        return view('videos.edit', compact('video', 'media'));
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'article_id' => ['nullable', 'exists:articles,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('videos', 'slug')->ignore($video->id),
            ],
            'description' => ['nullable', 'string'],
            'video_url' => ['required', 'url', 'max:255'],
            'thumbnail_media_id' => ['nullable', 'exists:media_libraries,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $video->update([
            'article_id' => $validated['article_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['slug'] ?? $validated['title'], $video->id),
            'description' => $validated['description'] ?? null,
            'video_url' => $validated['video_url'],
            'youtube_video_id' => $this->extractYoutubeId($validated['video_url']),
            'thumbnail_media_id' => $validated['thumbnail_media_id'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('videos.index')
            ->with('success', 'Video updated successfully.');
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return redirect()
            ->route('videos.index')
            ->with('success', 'Video deleted successfully.');
    }

    private function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $counter = 2;

        while (
            Video::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function extractYoutubeId(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        preg_match(
            '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{6,})/',
            $url,
            $matches
        );

        return $matches[1] ?? null;
    }
}