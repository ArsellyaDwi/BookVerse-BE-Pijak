<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\AIEmotionRule;
use App\Models\AiRecommendationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;
use Illuminate\Support\Carbon;

class EmotionController extends Controller
{
    /**
     * Detect emotion from text using AI service
     */
    public function detect(Request $request)
    {
        $request->validate([
            'text' => 'required|string|min:3'
        ]);

        try {
            // Panggil AI service Python (FastAPI)
            $response = Http::withHeader('X-API-Key', env('AI_SERVICE_KEY'))->post(env('AI_SERVICE_URL') . '/emotion/predict', [
                'text' => $request->text
            ]);

            if ($response->successful()) {
                $aiResult = $response->json();

                AiRecommendationLog::create([
                    'user_id' => $request->user('api')->id,
                    'input' => $request->text,
                    'result' => json_encode($aiResult['predictions']),
                    'create_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'predictions' => $aiResult['predictions'] ?? [],
                        'original_text' => $request->text
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'AI service error: ' . $response->status()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to AI service: ' . $e->getMessage()
            ], 500);
        }
    }

    public function recommend(Request $request)
    {
        $request->validate([
            'text' => 'required|string|min:3'
        ]);

        try {
            $response = Http::withHeader('X-API-Key', env('AI_SERVICE_KEY'))
                ->timeout(30)
                ->post(env('AI_SERVICE_URL') . '/emotion/predict', [
                    'text' => $request->text
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI service error: ' . $response->status()
                ], 500);
            }

            $aiResult = $response->json();
            $predictions = $aiResult['predictions'] ?? [];

            if (empty($predictions)) {
                return response()->json([
                    'success' => true,
                    'message' => 'No emotions detected from the text',
                    'data' => [
                        'predictions' => [],
                        'books' => [],
                        'original_text' => $request->text
                    ]
                ]);
            }

            // Get top emotion from predictions
            $topEmotion = $predictions[0]['emotion'];

            // Check if there's a rule for this emotion
            $emotionRule = AIEmotionRule::where('input_emotion', $topEmotion)->first();

            $searchEmotions = [];

            // If rule exists, use suggested output emotions from the rule
            if ($emotionRule && !empty($emotionRule->suggested_output_emotions)) {
                // Use the suggested output emotions from the rule
                $searchEmotions = $emotionRule->suggested_output_emotions;
                $appliedRule = [
                    'input_emotion' => $emotionRule->input_emotion,
                    'suggested_outputs' => $emotionRule->suggested_output_emotions
                ];
            } else {
                // No rule found, search by all predicted emotions
                // Extract all emotions from predictions (not just top emotion)
                $searchEmotions = array_unique(array_column($predictions, 'emotion'));
                $appliedRule = null;
            }

            // Search for books with the determined emotions
            $books = $this->searchBooksByEmotions($searchEmotions, $predictions);

            // Log the recommendation
            $log = AiRecommendationLog::create([
                'user_id' => $request->user()?->id,
                'input' => $request->text,
                'result' => $predictions,
                'create_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'predictions' => $predictions,
                    'top_emotion' => $topEmotion,
                    'rule_applied' => !is_null($appliedRule),
                    'applied_rule' => $appliedRule,
                    'search_emotions' => $searchEmotions,
                    'search_source' => $emotionRule ? 'rule_based' : 'prediction_based',
                    'books' => $books,
                    'original_text' => $request->text,
                    'total_books_found' => count($books)
                ],
                'log_id' => $log->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process recommendation: ' . $e->getMessage()
            ], 500);
        }
    }

    private function searchBooksByEmotions(array $searchEmotions, array $predictions = [])
    {
        if (empty($searchEmotions)) {
            return [];
        }

        // Get all books that have mood_tags
        $books = Book::whereNotNull('mood_tags')
            ->with(['genres', 'characters'])
            ->get();

        $matchedBooks = [];

        foreach ($books as $book) {
            $bookMoods = $book->mood_tags;

            if (empty($bookMoods) || !is_array($bookMoods)) {
                continue;
            }

            // Calculate match score for this book
            $matchScore = 0;
            $matchedEmotions = [];
            $totalConfidence = 0;

            foreach ($bookMoods as $bookMood) {
                $bookEmotion = $bookMood['emotion'] ?? null;
                $bookConfidence = $bookMood['confidence'] ?? 0;

                if (in_array($bookEmotion, $searchEmotions)) {
                    $matchScore++;
                    $matchedEmotions[] = $bookEmotion;
                    $totalConfidence += $bookConfidence;
                }
            }

            // If there's at least one match, include the book
            if ($matchScore > 0) {
                // Calculate relevance score based on matches and confidence
                $relevanceScore = $matchScore + ($totalConfidence / max(count($bookMoods), 1));

                // Add prediction confidence bonus
                $predictionMatchBonus = 0;
                foreach ($predictions as $prediction) {
                    if (in_array($prediction['emotion'], $searchEmotions)) {
                        $predictionMatchBonus += $prediction['confidence'];
                    }
                }
                $relevanceScore += $predictionMatchBonus;

                $matchedBooks[] = [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'description' => $book->description,
                    'cover_img' => $book->cover_img,
                    'rating' => $book->rating,
                    'price' => $book->price,
                    'genres' => $book->genres->pluck('name'),
                    'characters' => $book->characters->pluck('name'),
                    'mood_tags' => $book->mood_tags,
                    'matched_emotions' => $matchedEmotions,
                    'match_count' => $matchScore,
                    'relevance_score' => round($relevanceScore, 2)
                ];
            }
        }

        // Sort books by relevance score (highest first)
        usort($matchedBooks, function ($a, $b) {
            return $b['relevance_score'] <=> $a['relevance_score'];
        });

        // Limit to top 20 recommendations
        return array_slice($matchedBooks, 0, 20);
    }

    /**
     * Combined endpoint: detect emotion and get recommendations
     */
    public function analyzeAndRecommend(Request $request)
    {
        $request->validate([
            'text' => 'required|string|min:3'
        ]);

        try {
            // Step 1: Detect emotion from AI
            $response = Http::withHeader('X-API-Key', env('AI_SERVICE_KEY'))->post(env('AI_SERVICE_URL') . '/emotion/predict', [
                'text' => $request->text
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI service error'
                ], 500);
            }

            $aiResult = $response->json();
            $predictions = $aiResult['predictions'] ?? [];

            // Step 2: Get top emotion
            $topEmotion = !empty($predictions) ? $predictions[0]['emotion'] : 'happiness';

            // Step 3: Get book recommendations based on top emotion
            $emotionToGenres = [
                'happiness' => ['Fiction', 'Comedy', 'Romance', 'Adventure'],
                'sadness' => ['Self-Help', 'Motivational', 'Inspirational'],
                'anxiety' => ['Self-Help', 'Philosophy', 'Mindfulness'],
                'fear' => ['Motivational', 'Inspirational', 'Self-Help'],
                'relief' => ['Fiction', 'Romance', 'Comedy'],
                'love' => ['Romance', 'Poetry', 'Fiction'],
                'anger' => ['Fiction', 'Thriller', 'Action'],
                'loneliness' => ['Romance', 'Fiction', 'Self-Help']
            ];

            $genres = $emotionToGenres[$topEmotion] ?? ['Fiction'];

            $books = Book::whereHas('genres', function ($query) use ($genres) {
                $query->whereIn('name', $genres);
            })
                ->with('genres')
                ->limit(12)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'detected_emotions' => $predictions,
                    'top_emotion' => $topEmotion,
                    'recommended_books' => $books
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getEmotions()
    {
        try {
            $response = Http::get('http://localhost:5001/emotions');

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }

            $fallbackEmotions = [
                'happiness',
                'sadness',
                'anxiety',
                'fear',
                'anger',
                'love',
                'relief',
                'hope',
                'loneliness',
                'gratitude',
                'excitement',
                'surprise',
                'disappointment',
                'pride',
                'guilt'
            ];

            return response()->json([
                'success' => true,
                'data' => $fallbackEmotions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'data' => ['happiness', 'sadness', 'anxiety', 'fear', 'love', 'relief']
            ]);
        }
    }
}
