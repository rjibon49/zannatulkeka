<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'featured_media_id',
        'title',
        'subtitle',
        'slug',
        'description',
        'video_url',
        'status', // published, draft, schedule
        'published_at',
        'meta_title',
        'meta_description',
        'views_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Many-to-Many Relation for Multiple Categories
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function featuredImage()
    {
        return $this->belongsTo(MediaLibrary::class, 'featured_media_id');
    }
}