<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'headline',
        'short_intro',
        'bio',
        'profile_picture_id',
        'cover_media_id',
        'resume_pdf_id',
        'phone',
        'email',
        'address',
        'website_url',
        'facebook_url',
        'linkedin_url',
        'twitter_url',
        'instagram_url',
        'youtube_url',
        'github_url',
        'meta_title',
        'meta_description',
        'status',
    ];

    public function profilePicture(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'profile_picture_id');
    }

    public function coverImage(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'cover_media_id');
    }

    public function resumePdf(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'resume_pdf_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PortfolioItem::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function activeItems(): HasMany
    {
        return $this->hasMany(PortfolioItem::class)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function educationItems(): HasMany
    {
        return $this->hasMany(PortfolioItem::class)
            ->where('type', 'education')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function experienceItems(): HasMany
    {
        return $this->hasMany(PortfolioItem::class)
            ->where('type', 'experience')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function skillItems(): HasMany
    {
        return $this->hasMany(PortfolioItem::class)
            ->where('type', 'skill')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function projectItems(): HasMany
    {
        return $this->hasMany(PortfolioItem::class)
            ->where('type', 'project')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function publicationItems(): HasMany
    {
        return $this->hasMany(PortfolioItem::class)
            ->where('type', 'publication')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->name;
    }

    public function getSeoDescriptionAttribute(): ?string
    {
        return $this->meta_description ?: $this->short_intro;
    }
}