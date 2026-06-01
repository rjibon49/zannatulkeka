<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\MediaLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    // ১. আর্টিকেলের লিস্ট, সার্চ এবং ফিল্টার
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $month = $request->input('month'); // ফরম্যাট আসবে: YYYY-MM

        // ফিল্টার ড্রপডাউনে দেখানোর জন্য ক্যাটাগরিগুলো আনা হলো
        $categories = Category::where('status', 'active')->get();

        $articles = Article::with(['categories', 'featuredImage'])
            // Title দিয়ে সার্চ
            ->when($search, function($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            // Category দিয়ে ফিল্টার (যেহেতু Many-to-Many রিলেশন, তাই whereHas ব্যবহার করা হয়েছে)
            ->when($categoryId, function($query) use ($categoryId) {
                return $query->whereHas('categories', function($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                });
            })
            // Month এবং Year দিয়ে ফিল্টার
            ->when($month, function($query) use ($month) {
                $year = substr($month, 0, 4);
                $monthNum = substr($month, 5, 2);
                return $query->whereYear('created_at', $year)
                             ->whereMonth('created_at', $monthNum);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // পেজিনেশনে যেন ফিল্টার ডাটা হারিয়ে না যায়

        return view('articles.index', compact('articles', 'search', 'categories', 'categoryId', 'month'));
    }

    // ২. নতুন আর্টিকেল লেখার ফর্ম
    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $media = MediaLibrary::latest()->get(); 
        
        return view('articles.create', compact('categories', 'media'));
    }

    // ৩. ডাটাবেসে সেভ করা
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'categories' => 'required|array', // Multiple categories
            'categories.*' => 'exists:categories,id',
            'description' => 'required|string',
            'status' => 'required|in:published,draft,schedule',
            'published_at' => 'nullable|required_if:status,schedule|date',
            'featured_media_id' => 'nullable|exists:media_libraries,id',
        ]);

        // Publish Date Logic
        $publishDate = null;
        if ($request->status === 'published') {
            $publishDate = now();
        } elseif ($request->status === 'schedule') {
            $publishDate = $request->published_at;
        }

        $article = Article::create([
            'featured_media_id' => $request->featured_media_id,
            'title' => $request->title,
            'subtitle' => $request->subtitle, // সাবটাইটেল সেভ করার জন্য যুক্ত করা হয়েছে
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->title),
            'description' => $request->description,
            'status' => $request->status,
            'published_at' => $publishDate,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        // Pivot Data Save (Multiple Categories)
        $article->categories()->sync($request->categories);

        return redirect()->route('articles.index')->with('success', 'Article created successfully!');
    }

    // ৪. এডিট ফর্ম
    public function edit(Article $article)
    {
        $categories = Category::where('status', 'active')->get();
        $media = MediaLibrary::latest()->get();
        
        return view('articles.edit', compact('article', 'categories', 'media'));
    }

    // ৫. আপডেট করা
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
            'subtitle' => $request->subtitle, // সাবটাইটেল আপডেটের জন্য যুক্ত করা হয়েছে
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

    // ৬. ডিলিট করা
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Article deleted successfully!');
    }
}