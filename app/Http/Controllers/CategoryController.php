<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categories = Category::when($search, function($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->latest()->paginate(5)->withQueryString();
        $category = null;
        return view('categories.index', compact('categories', 'category', 'search'));
    }

    public function create()
    {
        return redirect()->route('categories.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug', // Slug ভ্যালিডেশন
        ]);

        Category::create([
            'name' => $request->name,
            // ইউজার Slug দিলে সেটি নেবে (slugify করে), না দিলে Name থেকে বানাবে
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->name),
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Request $request, Category $category)
    {
        $search = $request->input('search');
        $categories = Category::when($search, function($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->latest()->paginate(5)->withQueryString();
        return view('categories.index', compact('categories', 'category', 'search'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->name),
            'status' => $request->status,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}