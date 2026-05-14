<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookQuote extends Model
{
    protected $fillable = [
        'book_id', 
        'quote', 
        'mood', 
        'page_number',
        'user_id',
        'author_name',
        'is_approved',
        'likes_count',
        'is_admin',
        'is_anonymous'
    ];
    
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
  
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function savedBy()
    {
        return $this->hasMany(UserSavedQuote::class);
    }
    
    public function likes()
    {
        return $this->hasMany(QuoteLike::class);
    }
    
    // Helper to check if user can delete
    public function canDelete($userId)
    {
        return $this->user_id === $userId;
    }
}