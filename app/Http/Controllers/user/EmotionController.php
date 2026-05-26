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

            $emotionDistribution = [];
            $appliedRule = null;
            $searchEmotions = [];

            // If rule exists, use the emotion distribution from the rule
            if ($emotionRule) {
                // Get the emotion distribution based on the ratio
                $emotionDistribution = $emotionRule->calculateEmotionDistribution();
                $searchEmotions = array_keys($emotionDistribution);
                $appliedRule = [
                    'input_emotion' => $emotionRule->input_emotion,
                    'suggested_outputs' => $emotionRule->suggested_output_emotions,
                    'matched_outputs' => $emotionRule->matched_output_emotions,
                    'ratio' => $emotionRule->suggested_match_ratio,
                    'distribution' => $emotionDistribution
                ];
            } else {
                // No rule found, use predictions with equal distribution
                $searchEmotions = array_unique(array_column($predictions, 'emotion'));
                $equalWeight = 1 / count($searchEmotions);
                foreach ($searchEmotions as $emotion) {
                    $emotionDistribution[$emotion] = $equalWeight;
                }
                $appliedRule = null;
            }

            // Search for books with distribution-based weighting
            $books = $this->searchBooksByEmotionsWithDistribution($searchEmotions, $emotionDistribution, $predictions);

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
                    'emotion_distribution' => $emotionDistribution,
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

    /**
     * Search books using emotion distribution weights
     * This ensures books are selected proportionally based on the ratio of suggested vs matched emotions
     */
    private function searchBooksByEmotionsWithDistribution(array $searchEmotions, array $emotionDistribution, array $predictions = [], $totalBooks = 20)
    {
        if (empty($searchEmotions) || empty($emotionDistribution)) {
            return [];
        }

        // Get all books that have mood_tags
        $books = Book::whereNotNull('mood_tags')
            ->with(['genres', 'characters'])
            ->get();

        // Group books by the primary emotion they match
        $booksByEmotion = [];
        $bookScores = [];

        foreach ($books as $book) {
            $bookMoods = $book->mood_tags;

            if (empty($bookMoods) || !is_array($bookMoods)) {
                continue;
            }

            // Find which search emotions match this book
            $matchedEmotions = [];
            $bestMatchScore = 0;
            $primaryEmotion = null;
            $totalConfidence = 0;

            foreach ($bookMoods as $bookMood) {
                $bookEmotion = $bookMood['emotion'] ?? null;
                $bookConfidence = $bookMood['confidence'] ?? 0;

                if (in_array($bookEmotion, $searchEmotions)) {
                    $matchedEmotions[] = $bookEmotion;
                    $totalConfidence += $bookConfidence;

                    // Calculate match score based on emotion weight from distribution
                    $emotionWeight = $emotionDistribution[$bookEmotion] ?? 0;
                    $matchScore = $emotionWeight * (1 + $bookConfidence);

                    if ($matchScore > $bestMatchScore) {
                        $bestMatchScore = $matchScore;
                        $primaryEmotion = $bookEmotion;
                    }
                }
            }

            // If there's at least one match, store the book
            if (!empty($matchedEmotions) && $primaryEmotion) {
                $bookData = [
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
                    'primary_emotion' => $primaryEmotion,
                    'relevance_score' => $bestMatchScore,
                    'match_count' => count($matchedEmotions)
                ];

                // Group by primary emotion
                if (!isset($booksByEmotion[$primaryEmotion])) {
                    $booksByEmotion[$primaryEmotion] = [];
                }
                $booksByEmotion[$primaryEmotion][] = $bookData;

                // Store for scoring
                $bookScores[$book->id] = $bestMatchScore;
            }
        }

        // Sort books within each emotion by relevance score
        foreach ($booksByEmotion as $emotion => $bookList) {
            usort($booksByEmotion[$emotion], function ($a, $b) {
                return $b['relevance_score'] <=> $a['relevance_score'];
            });
        }

        // Select books based on distribution weights
        $selectedBooks = [];
        $booksNeeded = $totalBooks;

        // Calculate how many books to take from each emotion based on distribution
        $booksPerEmotion = [];
        foreach ($emotionDistribution as $emotion => $weight) {
            if (isset($booksByEmotion[$emotion])) {
                $allocatedCount = (int) round($weight * $totalBooks);
                $booksPerEmotion[$emotion] = min($allocatedCount, count($booksByEmotion[$emotion]));
                $booksNeeded -= $booksPerEmotion[$emotion];
            }
        }

        // Distribute remaining books to emotions that have more books available
        if ($booksNeeded > 0) {
            $remainingEmotions = array_keys($booksPerEmotion);
            $index = 0;
            while ($booksNeeded > 0 && !empty($remainingEmotions)) {
                $emotion = $remainingEmotions[$index % count($remainingEmotions)];
                if ($booksPerEmotion[$emotion] < count($booksByEmotion[$emotion])) {
                    $booksPerEmotion[$emotion]++;
                    $booksNeeded--;
                }
                $index++;

                // Remove emotions that have no more books
                $remainingEmotions = array_filter($remainingEmotions, function ($e) use ($booksPerEmotion, $booksByEmotion) {
                    return $booksPerEmotion[$e] < count($booksByEmotion[$e]);
                });
                $remainingEmotions = array_values($remainingEmotions);
            }
        }

        // Select books according to allocation
        foreach ($booksPerEmotion as $emotion => $count) {
            for ($i = 0; $i < $count && $i < count($booksByEmotion[$emotion]); $i++) {
                $selectedBooks[] = $booksByEmotion[$emotion][$i];
            }
        }

        // Sort final selection by relevance score
        usort($selectedBooks, function ($a, $b) {
            return $b['relevance_score'] <=> $a['relevance_score'];
        });

        return $selectedBooks;
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

            // Step 3: Check for emotion rule
            $emotionRule = AIEmotionRule::where('input_emotion', $topEmotion)->first();

            $searchEmotions = [];
            $emotionDistribution = [];

            if ($emotionRule) {
                $emotionDistribution = $emotionRule->calculateEmotionDistribution();
                $searchEmotions = array_keys($emotionDistribution);
            } else {
                // Fallback to genre-based recommendation if no rule exists
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
                        'rule_applied' => false,
                        'recommended_books' => $books
                    ]
                ]);
            }

            // Search books with distribution
            $books = $this->searchBooksByEmotionsWithDistribution($searchEmotions, $emotionDistribution, $predictions, 20);

            return response()->json([
                'success' => true,
                'data' => [
                    'detected_emotions' => $predictions,
                    'top_emotion' => $topEmotion,
                    'rule_applied' => true,
                    'emotion_distribution' => $emotionDistribution,
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
            // $response = Http::get('http://localhost:5001/emotions');

            // if ($response->successful()) {
            //     return response()->json([
            //         'success' => true,
            //         'data' => $response->json()
            //     ]);
            // }

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
