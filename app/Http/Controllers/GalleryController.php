<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\MediaLibrary;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('media')->latest()->paginate(12);
        return view('galleries.index', compact('galleries'));
    }

    public function create()
    {
        $media = MediaLibrary::latest()->get();
        return view('galleries.create', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'media_library_ids' => 'required|array', // আইডিগুলো অ্যারে হিসেবে আসবে
            'media_library_ids.*' => 'exists:media_libraries,id',
            'status' => 'required|in:active,inactive',
        ]);

        foreach ($request->media_library_ids as $id) {
            Gallery::create([
                'media_library_id' => $id,
                'title' => $request->title, // চাইলে লুপের ভেতরে টাইটেলও কাস্টমাইজ করতে পারেন
                'status' => $request->status,
            ]);
        }

        return redirect()->route('galleries.index')->with('success', 'Images added to gallery!');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return redirect()->route('galleries.index')->with('success', 'Deleted successfully!');
    }
}