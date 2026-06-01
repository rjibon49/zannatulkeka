<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // কে পোস্ট করেছে তা ট্র‍্যাক করার জন্য
        'title',
        'subtitle',
        'slug',
        'description',
        'featured_image', // মাইগ্রেশন অনুযায়ী এটি স্ট্রিং হিসেবে রাখা হয়েছে
        'video_url',
        'status', // published, draft
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

    // আর্টিকেলের লেখকের (User) সাথে রিলেশন
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}