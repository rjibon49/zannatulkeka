<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    // ডাটাবেসে যে ফিল্ডগুলোতে ডাটা সেভ হবে
    protected $fillable = [
        'name', 
        'designation', 
        'bio', 
        'profile_picture_id', 
        'phone', 
        'email', 
        'address'
    ];

    // প্রোফাইল পিকচারটি মিডিয়া লাইব্রেরি থেকে আনার রিলেশন
    public function profilePicture()
    {
        return $this->belongsTo(MediaLibrary::class, 'profile_picture_id');
    }
}