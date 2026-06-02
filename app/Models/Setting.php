<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_title',
        'site_description',
        'logo_media_id',
        'favicon_media_id',
        'banner_media_id',
        'heading',
        'subheading',
        'contact_email',
        'contact_phone',
        'address',
        'facebook_url',
        'linkedin_url',
        'twitter_url',
        'instagram_url',
        'youtube_url',
        'github_url',
        'default_meta_title',
        'default_meta_description',
        'default_og_media_id',
        'footer_text',
    ];

    public function logo(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'logo_media_id');
    }

    public function favicon(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'favicon_media_id');
    }

    public function banner(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'banner_media_id');
    }

    public function defaultOgImage(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'default_og_media_id');
    }

    public static function firstOrCreateDefault(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Zannatul Keka',
                'site_title' => 'Zannatul Keka Portfolio',
                'site_description' => 'Personal portfolio, articles, gallery and video archive.',
                'heading' => 'Zannatul Keka',
                'subheading' => 'Portfolio, Articles and Creative Works',
            ]
        );
    }

    public function getSeoTitleAttribute(): ?string
    {
        return $this->default_meta_title ?: $this->site_title;
    }

    public function getSeoDescriptionAttribute(): ?string
    {
        return $this->default_meta_description ?: $this->site_description;
    }
}