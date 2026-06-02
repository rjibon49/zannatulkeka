<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_media_id',
        'article_id',
        'meta_title',
        'meta_description',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function coverImage(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'cover_media_id');
    }

    public function media(): BelongsTo
    {
        return $this->coverImage();
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function activeImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('id');
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}