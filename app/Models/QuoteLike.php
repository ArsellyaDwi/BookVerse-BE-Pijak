<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteLike extends Model
{
    protected $fillable = ['quote_id', 'user_id'];
    
    public function quote()
    {
        return $this->belongsTo(BookQuote::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}