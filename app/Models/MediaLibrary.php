<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MediaLibrary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_name',
        'original_name',
        'file_path',
        'file_url',
        'mime_type',
        'extension',
        'file_size',
        'alt_text',
        'caption',
        'description',
        'type',
        'disk',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function usersAsProfilePicture(): HasMany
    {
        return $this->hasMany(User::class, 'profile_picture_id');
    }

    public function articlesAsFeaturedImage(): HasMany
    {
        return $this->hasMany(Article::class, 'featured_media_id');
    }

    public function articlesAsOgImage(): HasMany
    {
        return $this->hasMany(Article::class, 'og_media_id');
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class, 'media_library_id');
    }

    public function videosAsThumbnail(): HasMany
    {
        return $this->hasMany(Video::class, 'thumbnail_media_id');
    }

    public function portfoliosAsProfilePicture(): HasMany
    {
        return $this->hasMany(Portfolio::class, 'profile_picture_id');
    }

    public function portfoliosAsCover(): HasMany
    {
        return $this->hasMany(Portfolio::class, 'cover_media_id');
    }

    public function portfoliosAsResume(): HasMany
    {
        return $this->hasMany(Portfolio::class, 'resume_pdf_id');
    }

    public function getUrlAttribute(): string
    {
        if (empty($this->file_path)) {
            return '';
        }

        $path = str_replace('\\', '/', $this->file_path);

        if (str_starts_with($path, '/storage/')) {
            $path = str_replace('/storage/', '', $path);
        }

        if (str_starts_with($path, 'storage/')) {
            $path = str_replace('storage/', '', $path);
        }

        $path = ltrim($path, '/');

        return url('storage/' . $path);
    }

    public function getReadableSizeAttribute(): string
    {
        if (empty($this->file_size)) {
            return '0 KB';
        }

        $size = (float) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . ' ' . $units[$i];
    }
}