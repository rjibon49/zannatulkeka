<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'title',
        'slug',
        'description',
        'video_url',
        'youtube_video_id',
        'thumbnail_media_id',
        'meta_title',
        'meta_description',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'thumbnail_media_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function getSeoDescriptionAttribute(): ?string
    {
        return $this->meta_description ?: $this->description;
    }

    public function getEmbedUrlAttribute(): ?string
    {
        if (!empty($this->youtube_video_id)) {
            return 'https://www.youtube.com/embed/' . $this->youtube_video_id;
        }

        return null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}