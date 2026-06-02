<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\MediaLibrary;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $tagId = $request->input('tag_id');
        $status = $request->input('status');
        $month = $request->input('month');

        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        $tags = Tag::where('status', 'active')
            ->orderBy('name')
            ->get();

        $articles = Article::with(['categories', 'tags', 'featuredImage', 'author'])
            ->when(auth()->user()?->isContributor(), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('subtitle', 'like', '%' . $search . '%')
                        ->orWhere('excerpt', 'like', '%' . $search . '%');
                });
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->whereHas('categories', function ($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                });
            })
            ->when($tagId, function ($query) use ($tagId) {
                $query->whereHas('tags', function ($q) use ($tagId) {
                    $q->where('tags.id', $tagId);
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($month, function ($query) use ($month) {
                $query->whereYear('created_at', substr($month, 0, 4))
                    ->whereMonth('created_at', substr($month, 5, 2));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('articles.index', compact(
            'articles',
            'search',
            'categories',
            'tags',
            'categoryId',
            'tagId',
            'status',
            'month'
        ));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        $tags = Tag::where('status', 'active')
            ->orderBy('name')
            ->get();

        $media = MediaLibrary::latest()->get();

        return view('articles.create', compact('categories', 'tags', 'media'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'featured_media_id' => ['nullable', 'exists:media_libraries,id'],
            'og_media_id' => ['nullable', 'exists:media_libraries,id'],

            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:articles,slug'],

            'excerpt' => ['nullable', 'string'],
            'description' => ['required', 'string'],

            'video_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'published_at' => ['nullable', 'date'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'canonical_url' => ['nullable', 'url', 'max:255'],

            'is_featured' => ['nullable', 'boolean'],

            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],

            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        $slug = $this->generateUniqueSlug(
            $validated['slug'] ?? $validated['title']
        );

        $publishedAt = null;

        if ($validated['status'] === 'published') {
            $publishedAt = $validated['published_at'] ?? now();
        }

        $article = Article::create([
            'user_id' => auth()->id(),
            'featured_media_id' => $validated['featured_media_id'] ?? null,
            'og_media_id' => $validated['og_media_id'] ?? null,

            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'slug' => $slug,

            'excerpt' => $validated['excerpt'] ?? null,
            'description' => $validated['description'],

            'video_url' => $validated['video_url'] ?? null,
            'youtube_video_id' => $this->extractYoutubeId($validated['video_url'] ?? null),

            'status' => $validated['status'],
            'published_at' => $publishedAt,

            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'canonical_url' => $validated['canonical_url'] ?? null,

            'is_featured' => $request->boolean('is_featured'),
            'views_count' => 0,
        ]);

        $article->categories()->sync($validated['categories'] ?? []);
        $article->tags()->sync($validated['tags'] ?? []);

        return redirect()
            ->route('articles.index')
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        $this->authorizeArticleAccess($article);

        $article->load(['categories', 'tags', 'featuredImage', 'ogImage']);

        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        $tags = Tag::where('status', 'active')
            ->orderBy('name')
            ->get();

        $media = MediaLibrary::latest()->get();

        return view('articles.edit', compact('article', 'categories', 'tags', 'media'));
    }

    public function update(Request $request, Article $article)
    {
        $this->authorizeArticleAccess($article);

        $validated = $request->validate([
            'featured_media_id' => ['nullable', 'exists:media_libraries,id'],
            'og_media_id' => ['nullable', 'exists:media_libraries,id'],

            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('articles', 'slug')->ignore($article->id),
            ],

            'excerpt' => ['nullable', 'string'],
            'description' => ['required', 'string'],

            'video_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'published_at' => ['nullable', 'date'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'canonical_url' => ['nullable', 'url', 'max:255'],

            'is_featured' => ['nullable', 'boolean'],

            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],

            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        $slug = $validated['slug']
            ? $this->generateUniqueSlug($validated['slug'], $article->id)
            : $this->generateUniqueSlug($validated['title'], $article->id);

        $publishedAt = $article->published_at;

        if ($validated['status'] === 'published') {
            $publishedAt = $validated['published_at'] ?? $article->published_at ?? now();
        }

        if (in_array($validated['status'], ['draft', 'archived'], true)) {
            $publishedAt = null;
        }

        $article->update([
            'featured_media_id' => $validated['featured_media_id'] ?? null,
            'og_media_id' => $validated['og_media_id'] ?? null,

            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'slug' => $slug,

            'excerpt' => $validated['excerpt'] ?? null,
            'description' => $validated['description'],

            'video_url' => $validated['video_url'] ?? null,
            'youtube_video_id' => $this->extractYoutubeId($validated['video_url'] ?? null),

            'status' => $validated['status'],
            'published_at' => $publishedAt,

            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'canonical_url' => $validated['canonical_url'] ?? null,

            'is_featured' => $request->boolean('is_featured'),
        ]);

        $article->categories()->sync($validated['categories'] ?? []);
        $article->tags()->sync($validated['tags'] ?? []);

        return redirect()
            ->route('articles.index')
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $this->authorizeArticleAccess($article);

        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('success', 'Article deleted successfully.');
    }

    private function authorizeArticleAccess(Article $article): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        if ($user->isContributor() && (int) $article->user_id !== (int) $user->id) {
            abort(403, 'You do not have permission to manage this article.');
        }
    }

    private function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $counter = 2;

        while (
            Article::where('slug', $slug)
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