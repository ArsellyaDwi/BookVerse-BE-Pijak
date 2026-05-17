<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AIEmotionRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIEmotionRuleController extends Controller
{
    public function index()
    {
        $response = Http::withHeader('X-API-Key', env('AI_SERVICE_KEY'))
            ->get(env('AI_SERVICE_URL') . '/emotion/emotions');

        $availableEmotions = $response->json()['emotions'];

        $rules = AIEmotionRule::all()->keyBy('input_emotion');

        return view('pages.ai.emotion-rules.index', compact('availableEmotions', 'rules'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'input_emotion' => 'required|string',
            'suggested_output_emotions' => 'required|array',
            'suggested_output_emotions.*' => 'string'
        ]);

        $rule = AIEmotionRule::updateOrCreate(
            ['input_emotion' => $request->input_emotion],
            ['suggested_output_emotions' => $request->suggested_output_emotions]
        );

        $isNew = $rule->wasRecentlyCreated;

        return response()->json([
            'success' => true,
            'message' => $isNew ? 'Emotion rule created successfully' : 'Emotion rule updated successfully',
            'rule' => $rule,
            'is_new' => $isNew
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'rules' => 'required|array',
            'rules.*.input_emotion' => 'required|string',
            'rules.*.suggested_output_emotions' => 'nullable|array'
        ]);

        $updated = 0;
        $created = 0;

        foreach ($request->rules as $ruleData) {
            $rule = AIEmotionRule::updateOrCreate(
                ['input_emotion' => $ruleData['input_emotion']],
                ['suggested_output_emotions' => $ruleData['suggested_output_emotions']]
            );

            if ($rule->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Updated {$updated} rules and created {$created} new rules",
            'updated' => $updated,
            'created' => $created
        ]);
    }
}
