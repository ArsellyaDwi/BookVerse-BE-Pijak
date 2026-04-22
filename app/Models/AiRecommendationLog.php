<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiRecommendationLog extends Model
{
    protected $table = 'ai_recommendation_logs';

    protected $fillable = [
        'user_id',
        'input',
        'result',
        'create_at',
    ];

    protected $casts = [
        'create_at' => 'datetime',
        'result' => 'array', // Automatically cast JSON to array
    ];

    public $timestamps = false; // Using custom create_at instead of created_at

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recommendationItems()
    {
        return $this->hasMany(AiRecommendationItem::class, 'ai_recommendation_log_id');
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'ai_recommendation_items', 'ai_recommendation_log_id', 'book_id');
    }
}
