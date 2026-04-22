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
        'price'
    ];

    protected $casts = [
        'rating' => 'float',
        'price' => 'decimal:2',
        'publish_date' => 'date'
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
