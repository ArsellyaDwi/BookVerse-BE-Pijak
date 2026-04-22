<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiEmotionDataset extends Model
{
    protected $fillable = [
        'labels',
        'text',
    ];
}
