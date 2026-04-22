<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiTrainingLog extends Model
{
    protected $table = 'ai_training_logs';

    protected $fillable = [
        'total_loss',
        'total_time',
    ];

    protected $casts = [
        'total_loss' => 'float',
        'total_time' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
