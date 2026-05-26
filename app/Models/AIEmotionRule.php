<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIEmotionRule extends Model
{
    protected $table = 'ai_emotion_rules';

    protected $fillable = [
        'input_emotion',
        'suggested_output_emotions',
        'suggested_match_ratio',
        'matched_output_emotions'
    ];

    protected $casts = [
        'suggested_output_emotions' => 'array',
        'matched_output_emotions' => 'array'
    ];

    public function calculateEmotionDistribution()
    {
        $suggestedOutputs = $this->suggested_output_emotions ?? [];
        $matchedOutputs = $this->matched_output_emotions ?? [];
        $ratio = $this->suggested_match_ratio ?? 50; // Default 50% if not set

        $distribution = [];

        if (!empty($suggestedOutputs)) {
            $suggestedWeight = $ratio / 100;
            $perEmotionWeight = $suggestedWeight / count($suggestedOutputs);

            foreach ($suggestedOutputs as $emotion) {
                $distribution[$emotion] = ($distribution[$emotion] ?? 0) + $perEmotionWeight;
            }
        }

        if (!empty($matchedOutputs)) {
            $matchedWeight = (100 - $ratio) / 100;
            $perEmotionWeight = $matchedWeight / count($matchedOutputs);

            foreach ($matchedOutputs as $emotion) {
                $distribution[$emotion] = ($distribution[$emotion] ?? 0) + $perEmotionWeight;
            }
        }

        $total = array_sum($distribution);
        if ($total > 0) {
            foreach ($distribution as $emotion => $weight) {
                $distribution[$emotion] = round($weight / $total, 4);
            }
        }

        return $distribution;
    }
}
