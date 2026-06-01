<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'banner_media_id',
        'heading',
        'subheading',
        'contact_email'
    ];

    // ব্যানার ইমেজের জন্য মিডিয়া লাইব্রেরির সাথে রিলেশন
    public function banner()
    {
        return $this->belongsTo(MediaLibrary::class, 'banner_media_id');
    }
}