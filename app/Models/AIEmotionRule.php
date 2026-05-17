<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIEmotionRule extends Model
{
    protected $table = 'ai_emotion_rules';

    protected $fillable = [
        'input_emotion',
        'suggested_output_emotions'
    ];

    protected $casts = [
        'suggested_output_emotions' => 'array',
    ];
}
