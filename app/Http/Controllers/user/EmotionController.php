<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
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

    /**
     * Get book recommendations based on detected emotion
     */
    public function recommend(Request $request)
    {
        $request->validate([
            'emotion' => 'required|string'
        ]);

        // Mapping emotion to book genres
        $emotionToGenres = [
            'happiness' => ['Fiction', 'Comedy', 'Romance', 'Adventure'],
            'joy' => ['Fiction', 'Comedy', 'Romance'],
            'sadness' => ['Self-Help', 'Motivational', 'Inspirational', 'Philosophy'],
            'anxiety' => ['Self-Help', 'Philosophy', 'Mindfulness', 'Motivational'],
            'fear' => ['Motivational', 'Inspirational', 'Self-Help', 'Thriller'],
            'relief' => ['Fiction', 'Romance', 'Comedy', 'Adventure'],
            'love' => ['Romance', 'Poetry', 'Fiction', 'Drama'],
            'anger' => ['Fiction', 'Thriller', 'Action', 'Mystery'],
            'loneliness' => ['Romance', 'Fiction', 'Self-Help', 'Philosophy'],
            'gratitude' => ['Self-Help', 'Inspirational', 'Philosophy'],
            'hope' => ['Motivational', 'Inspirational', 'Self-Help'],
            'surprise' => ['Mystery', 'Thriller', 'Science Fiction'],
            'disappointment' => ['Self-Help', 'Motivational', 'Fiction'],
            'frustration' => ['Self-Help', 'Motivational', 'Action'],
            'guilt' => ['Self-Help', 'Philosophy', 'Fiction'],
            'pride' => ['Biography', 'Motivational', 'History'],
            'excitement' => ['Adventure', 'Science Fiction', 'Fantasy'],
            'confusion' => ['Philosophy', 'Self-Help', 'Mystery'],
            'disgust' => ['Self-Help', 'Philosophy'],
            'embarrassment' => ['Comedy', 'Romance', 'Fiction'],
            'jealousy' => ['Romance', 'Drama', 'Thriller']
        ];

        // Get genres based on emotion
        $genres = $emotionToGenres[$request->emotion] ?? ['Fiction', 'Self-Help'];

        // Query books with those genres
        $books = Book::whereHas('genres', function ($query) use ($genres) {
            $query->whereIn('name', $genres);
        })
            ->with('genres')
            ->limit(12)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'emotion' => $request->emotion,
                'recommended_genres' => $genres,
                'books' => $books
            ]
        ]);
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
