<?php

namespace App\Http\Controllers;

use App\Models\MediaLibrary;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaLibraryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $media = MediaLibrary::with('uploader')
            ->when(auth()->user()?->isContributor(), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($search, function ($query) use ($search) {
                $query->where('file_name', 'like', '%' . $search . '%')
                    ->orWhere('original_name', 'like', '%' . $search . '%')
                    ->orWhere('alt_text', 'like', '%' . $search . '%');
            })
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('media.index', compact('media', 'search', 'type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['nullable'],
            'file.*' => ['file', 'mimes:jpeg,png,jpg,webp,pdf,doc,docx,mp4,mp3,wav', 'max:10240'],

            'files' => ['nullable'],
            'files.*' => ['file', 'mimes:jpeg,png,jpg,webp,pdf,doc,docx,mp4,mp3,wav', 'max:10240'],

            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $files = $this->normalizeFiles($request);

        if (count($files) === 0) {
            return redirect()
                ->back()
                ->with('error', 'Please select at least one valid file.');
        }

        Storage::disk('public')->makeDirectory('uploads/media');

        $uploadedCount = 0;

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $originalNameWithExtension = $file->getClientOriginalName();
            $originalNameWithoutExtension = pathinfo($originalNameWithExtension, PATHINFO_FILENAME);

            if ($this->isConvertibleImage($file)) {
                $stored = $this->storeImageAsWebp($file);
            } else {
                $stored = $this->storeOriginalFile($file);
            }

            MediaLibrary::create([
                'user_id' => auth()->id(),

                'file_name' => $stored['file_name'],
                'original_name' => $originalNameWithExtension,

                'file_path' => $stored['file_path'],
                'file_url' => $stored['file_url'],

                'mime_type' => $stored['mime_type'],
                'extension' => $stored['extension'],
                'file_size' => $stored['file_size'],

                'alt_text' => $request->alt_text ?: $originalNameWithoutExtension,
                'caption' => $request->caption,
                'description' => $request->description,

                'type' => $stored['type'],
                'disk' => 'public',
            ]);

            $uploadedCount++;
        }

        return redirect()
            ->back()
            ->with('success', $uploadedCount . ' file(s) uploaded successfully.');
    }

    public function update(Request $request, MediaLibrary $medium)
    {
        $this->authorizeMediaAccess($medium);

        $validated = $request->validate([
            'file_name' => ['required', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $medium->update([
            'file_name' => $validated['file_name'],
            'alt_text' => $validated['alt_text'] ?? null,
            'caption' => $validated['caption'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Media information updated successfully.');
    }

    public function destroy(MediaLibrary $medium)
    {
        $this->authorizeMediaAccess($medium);

        $path = $medium->file_path;

        if (str_starts_with((string) $path, '/storage/')) {
            $path = str_replace('/storage/', '', $path);
        }

        if ($path && Storage::disk($medium->disk ?? 'public')->exists($path)) {
            Storage::disk($medium->disk ?? 'public')->delete($path);
        }

        $medium->delete();

        return redirect()
            ->back()
            ->with('success', 'Media deleted successfully.');
    }

    private function normalizeFiles(Request $request): array
    {
        $files = [];

        if ($request->hasFile('file')) {
            $fileInput = $request->file('file');
            $files = array_merge($files, is_array($fileInput) ? $fileInput : [$fileInput]);
        }

        if ($request->hasFile('files')) {
            $filesInput = $request->file('files');
            $files = array_merge($files, is_array($filesInput) ? $filesInput : [$filesInput]);
        }

        return $files;
    }

    private function isConvertibleImage(UploadedFile $file): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType() ?? '';

        return str_starts_with($mime, 'image/')
            && in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true);
    }

    private function storeImageAsWebp(UploadedFile $file): array
    {
        if (!function_exists('imagewebp')) {
            return $this->storeOriginalFile($file);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($originalName) ?: 'image';

        $fileName = now()->format('YmdHis') . '_' . uniqid() . '_' . $safeName . '.webp';
        $filePath = 'uploads/media/' . $fileName;
        $absolutePath = storage_path('app/public/' . $filePath);

        $image = $this->createImageResource($file);

        if (!$image) {
            return $this->storeOriginalFile($file);
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $quality = 82;
        $saved = imagewebp($image, $absolutePath, $quality);

        imagedestroy($image);

        if (!$saved || !file_exists($absolutePath)) {
            return $this->storeOriginalFile($file);
        }

        return [
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_url' => Storage::disk('public')->url($filePath),
            'mime_type' => 'image/webp',
            'extension' => 'webp',
            'file_size' => filesize($absolutePath),
            'type' => 'image',
        ];
    }

    private function createImageResource(UploadedFile $file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        return match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'webp' => @imagecreatefromwebp($path),
            default => null,
        };
    }

    private function storeOriginalFile(UploadedFile $file): array
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());
        $safeName = Str::slug($originalName) ?: 'file';

        $fileName = now()->format('YmdHis') . '_' . uniqid() . '_' . $safeName . '.' . $extension;
        $filePath = $file->storeAs('uploads/media', $fileName, 'public');

        return [
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_url' => Storage::disk('public')->url($filePath),
            'mime_type' => $file->getMimeType(),
            'extension' => $extension,
            'file_size' => $file->getSize(),
            'type' => $this->detectFileType($file),
        ];
    }

    private function detectFileType(UploadedFile $file): string
    {
        $mime = $file->getMimeType() ?? '';
        $extension = strtolower($file->getClientOriginalExtension());

        if (str_starts_with($mime, 'image/')) {
            return 'image';
        }

        if (str_starts_with($mime, 'video/')) {
            return 'video';
        }

        if (str_starts_with($mime, 'audio/')) {
            return 'audio';
        }

        if (in_array($extension, ['pdf', 'doc', 'docx'], true)) {
            return 'document';
        }

        return 'other';
    }

    private function authorizeMediaAccess(MediaLibrary $media): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        if ($user->isContributor() && (int) $media->user_id !== (int) $user->id) {
            abort(403, 'You do not have permission to manage this media file.');
        }
    }
}