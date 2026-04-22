<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    public function items()
    {
        return $this->hasMany(WishlistItem::class);
    }
}
