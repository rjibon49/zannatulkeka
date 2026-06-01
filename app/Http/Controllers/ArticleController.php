<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\MediaLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $month = $request->input('month');

        $categories = Category::where('status', 'active')->get();

        $articles = Article::with(['categories', 'featuredImage', 'author'])
            ->when($search, fn($query) => $query->where('title', 'like', '%' . $search . '%'))
            ->when($categoryId, fn($query) => $query->whereHas('categories', fn($q) => $q->where('categories.id', $categoryId)))
            ->when($month, function($query) use ($month) {
                return $query->whereYear('created_at', substr($month, 0, 4))
                             ->whereMonth('created_at', substr($month, 5, 2));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('articles.index', compact('articles', 'search', 'categories', 'categoryId', 'month'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $media = MediaLibrary::latest()->get(); 
        return view('articles.create', compact('categories', 'media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'description' => 'required|string',
            'status' => 'required|in:published,draft,schedule',
            'published_at' => 'nullable|required_if:status,schedule|date',
            'featured_media_id' => 'nullable|exists:media_libraries,id',
        ]);

        $publishDate = $request->status === 'published' ? now() : ($request->status === 'schedule' ? $request->published_at : null);

        $article = Article::create([
            'user_id' => auth()->id(),
            'featured_media_id' => $request->featured_media_id,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->title),
            'description' => $request->description,
            'status' => $request->status,
            'published_at' => $publishDate,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        $article->categories()->sync($request->categories);
        return redirect()->route('articles.index')->with('success', 'Article created successfully!');
    }

    public function edit(Article $article)
    {
        $categories = Category::where('status', 'active')->get();
        $media = MediaLibrary::latest()->get();
        return view('articles.edit', compact('article', 'categories', 'media'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'description' => 'required|string',
            'status' => 'required|in:published,draft,schedule',
            'published_at' => 'nullable|required_if:status,schedule|date',
            'featured_media_id' => 'nullable|exists:media_libraries,id',
        ]);

        $publishDate = $article->published_at;
        if ($request->status === 'published' && !$article->published_at) {
            $publishDate = now();
        } elseif ($request->status === 'schedule') {
            $publishDate = $request->published_at;
        }

        $article->update([
            'featured_media_id' => $request->featured_media_id,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->title),
            'description' => $request->description,
            'status' => $request->status,
            'published_at' => $publishDate,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        $article->categories()->sync($request->categories);
        return redirect()->route('articles.index')->with('success', 'Article updated successfully!');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Article deleted successfully!');
    }
}