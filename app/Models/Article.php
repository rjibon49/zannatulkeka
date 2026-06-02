<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'featured_media_id',
        'title',
        'subtitle',
        'slug',
        'excerpt',
        'description',
        'video_url',
        'youtube_video_id',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'canonical_url',
        'og_media_id',
        'views_count',
        'is_featured',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'featured_media_id');
    }

    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'og_media_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'article_category')
            ->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag')
            ->withTimestamps();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function getSeoDescriptionAttribute(): ?string
    {
        return $this->meta_description ?: $this->excerpt;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}