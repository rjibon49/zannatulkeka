<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'media_library_id',
        'article_id',
        'status',
    ];

    // মিডিয়া লাইব্রেরির সাথে রিলেশন (ছবিটি পাওয়ার জন্য)
    public function media()
    {
        return $this->belongsTo(MediaLibrary::class, 'media_library_id');
    }

    // আর্টিকেলের সাথে রিলেশন
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}