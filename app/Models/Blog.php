<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'short_description', 'content',
        'featured_image', 'author', 'views', 'likes', 'read_time',
        'published_at', 'is_published'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($blog) {
            $blog->slug = Str::slug($blog->title);
        });
    }
}