<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'status'
    ];

    // একটি ক্যাটাগরিতে অনেক আর্টিকেল থাকতে পারে
    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}