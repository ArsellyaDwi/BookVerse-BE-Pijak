<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    protected $fillable = [
        'book_id',
        'wishlist_id',
    ];

    public function book() {
        return $this->belongsTo(Book::class);
    }
}
