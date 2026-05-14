<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookQuote extends Model
{
    protected $fillable = ['book_id', 'quote', 'mood', 'page_number'];
    
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    
    public function savedBy()
    {
        return $this->hasMany(UserSavedQuote::class);
    }
}