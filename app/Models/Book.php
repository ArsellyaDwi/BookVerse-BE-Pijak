<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'series',
        'author',
        'rating',
        'description',
        'language',
        'isbn',
        'stock',
        'pages',
        'publisher',
        'publish_date',
        'ratings',
        'cover_img',
        'price',
        'mood_tags'
    ];

    protected $casts = [
        'rating' => 'float',
        'price' => 'decimal:2',
        'publish_date' => 'date',
        'mood_tags' => 'array', // json of [{"emotion": "excitement", "confidence": 0.36395978927612305}, {"emotion": "fear", "confidence": 0.28080686926841736}]
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genre');
    }

    public function characters()
    {
        return $this->belongsToMany(Character::class, 'book_character');
    }
}
