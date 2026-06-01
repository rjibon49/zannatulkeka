<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // ডাটাবেসের এই ফিল্ডগুলোতে আমরা সরাসরি ডাটা ইনসার্ট করতে পারবো
    protected $fillable = [
        'name',
        'slug',
        'status'
    ];
}