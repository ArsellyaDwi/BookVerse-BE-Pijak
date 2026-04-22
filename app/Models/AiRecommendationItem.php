<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiRecommendationItem extends Model
{
    protected $table = 'ai_recommendation_items';

    protected $fillable = [
        'ai_recommendation_log_id',
        'book_id',
    ];

    public $timestamps = false;

    public function recommendationLog()
    {
        return $this->belongsTo(AiRecommendationLog::class, 'ai_recommendation_log_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
