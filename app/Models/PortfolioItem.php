<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioItem extends Model
{
    use HasFactory;

    public const TYPES = [
        'work_identity',
        'education',
        'experience',
        'skill',
        'service',
        'project',
        'achievement',
        'award',
        'book',
        'publication',
        'certificate',
        'social_link',
    ];

    protected $fillable = [
        'portfolio_id',
        'type',
        'title',
        'subtitle',
        'organization_name',
        'location',
        'start_date',
        'end_date',
        'period',
        'url',
        'media_library_id',
        'description',
        'sort_order',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'sort_order' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'media_library_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}