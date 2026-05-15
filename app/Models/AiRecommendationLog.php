<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'result' => 'array', // Automatically decode JSON to array
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest User'
        ]);
    }

    // Helper method to get top emotion
    public function getTopEmotionAttribute()
    {
        if (empty($this->result) || !is_array($this->result)) {
            return null;
        }
        return $this->result[0] ?? null;
    }

    // Helper method to get all emotions
    public function getEmotionsListAttribute()
    {
        if (empty($this->result) || !is_array($this->result)) {
            return [];
        }
        return $this->result;
    }

    // Helper method to get emotion color
    public function getEmotionColorAttribute()
    {
        $colors = [
            'happiness' => 'green',
            'sadness' => 'blue',
            'anger' => 'red',
            'fear' => 'purple',
            'love' => 'pink',
            'gratitude' => 'teal',
            'relief' => 'indigo',
        ];

        $topEmotion = $this->top_emotion;
        return $colors[$topEmotion['emotion'] ?? ''] ?? 'gray';
    }
}
