<?php

namespace App\Http\Controllers;

use App\Models\MediaLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaLibraryController extends Controller
{
    // ১. গ্যালারি লিস্ট এবং সার্চ
    public function index(Request $request)
    {
        $search = $request->input('search');
        $media = MediaLibrary::when($search, function($query) use ($search) {
            return $query->where('file_name', 'like', '%' . $search . '%');
        })->latest()->paginate(12)->withQueryString();

        return view('media.index', compact('media', 'search'));
    }

    // ২. মাল্টিপল ছবি WebP তে কনভার্ট করে আপলোড
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|array', // মাল্টিপল ফাইলের জন্য array করা হয়েছে
            'file.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', 
        ]);

        if ($request->hasFile('file')) {
            $manager = new ImageManager(new Driver());
            $uploadedCount = 0;

            foreach ($request->file('file') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                
                // uniqid() যুক্ত করা হয়েছে যাতে একসাথে অনেক ছবি আপলোডে নাম কনফ্লিক্ট না করে
                $fileName = time() . '_' . uniqid() . '_' . Str::slug($originalName) . '.webp';
                
                $image = $manager->read($file);
                $encodedImage = $image->toWebp(80); 
                
                $filePath = 'uploads/media/' . $fileName;
                Storage::disk('public')->put($filePath, (string) $encodedImage);

                MediaLibrary::create([
                    'file_name' => $originalName . '.webp', // ইউজার ফ্রেন্ডলি নাম দেখানোর জন্য
                    'file_path' => '/storage/' . $filePath,
                    'alt_text' => $originalName,
                ]);
                $uploadedCount++;
            }

            return redirect()->back()->with('success', $uploadedCount . ' images converted and uploaded successfully!');
        }

        return redirect()->back()->with('error', 'Please select valid images.');
    }

    // ৩. ছবির নাম পরিবর্তন (রিনেম)
    public function update(Request $request, $id)
    {
        $request->validate([
            'file_name' => 'required|string|max:255',
        ]);

        // সঠিক আইডি দিয়ে ডাটাবেস থেকে ছবিটি খুঁজে বের করা
        $media = MediaLibrary::findOrFail($id);

        $newName = $request->file_name;
        
        // এক্সটেনশন না থাকলে .webp যুক্ত করে দেওয়া
        if (!str_ends_with($newName, '.webp') && !str_ends_with($newName, '.jpg') && !str_ends_with($newName, '.png')) {
            $newName .= '.webp';
        }

        $media->update([
            'file_name' => $newName,
            'alt_text' => pathinfo($newName, PATHINFO_FILENAME),
        ]);

        return redirect()->back()->with('success', 'File renamed successfully!');
    }

    // ৪. ছবি ডিলিট
    public function destroy($id)
    {
        // সঠিক আইডি দিয়ে ডাটাবেস থেকে ছবিটি খুঁজে বের করা
        $media = MediaLibrary::findOrFail($id);

        $path = str_replace('/storage/', '', $media->file_path);
        
        // স্টোরেজ থেকে আসল ছবিটি মুছে ফেলা
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        // ডাটাবেস থেকে রেকর্ড মুছে ফেলা
        $media->delete();

        return redirect()->back()->with('success', 'Image deleted successfully!');
    }
}