<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookQuote;
use App\Models\QuoteLike;
use App\Models\UserSavedQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QuoteController extends Controller
{
    /**
     * Get quote of the day
     */
    public function getQuoteOfDay()
    {
        $quote = BookQuote::with('book')->inRandomOrder()->first();

        return response()->json([
            'success' => true,
            'data' => $quote
        ]);
    }

    /**
     * Get quotes based on mood from AI
     */
    public function getQuotesByMood(Request $request)
    {
        $request->validate([
            'text' => 'required|string|min:3'
        ]);

        try {
            $aiResponse = Http::withHeader('X-API-Key', env('AI_SERVICE_KEY'))->post(env('AI_SERVICE_URL') . '/emotion/predict', [
                'text' => $request->text
            ]);


            if (!$aiResponse->successful()) {
                return $this->getFallbackQuotes($request->text);
            }

            $aiResult = $aiResponse->json();
            $predictions = $aiResult['predictions'] ?? [];

            $topEmotion = !empty($predictions) ? $predictions[0]['emotion'] : 'happiness';
            $confidence = !empty($predictions) ? $predictions[0]['confidence'] : 0;

            $quotes = BookQuote::with('book')
                ->where('mood', $topEmotion)
                ->inRandomOrder()
                ->limit(5)
                ->get();

            if ($quotes->isEmpty()) {
                $quotes = BookQuote::with('book')
                    ->inRandomOrder()
                    ->limit(5)
                    ->get();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'detected_mood' => $topEmotion,
                    'confidence' => round($confidence * 100),
                    'original_text' => $request->text,
                    'quotes' => $quotes
                ]
            ]);
        } catch (\Exception $e) {
            return $this->getFallbackQuotes($request->text);
        }
    }

    /**
     * Fallback quotes when AI service is down
     */
    private function getFallbackQuotes($text)
    {
        $mood = $this->detectMoodFromText($text);

        $quotes = BookQuote::with('book')
            ->where('mood', $mood)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        if ($quotes->isEmpty()) {
            $quotes = BookQuote::with('book')->inRandomOrder()->limit(5)->get();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'detected_mood' => $mood,
                'confidence' => 70,
                'original_text' => $text,
                'quotes' => $quotes,
                'fallback' => true
            ]
        ]);
    }

    /**
     * Simple mood detection from text (fallback)
     */
    private function detectMoodFromText($text)
    {
        $text = strtolower($text);

        $moodKeywords = [
            'anger' => ['angry', 'mad', 'frustrated', 'annoyed', 'rage', 'marah'],
            'sadness' => ['sad', 'depressed', 'down', 'hurt', 'sedih', 'kecewa'],
            'happiness' => ['happy', 'joy', 'glad', 'pleased', 'senang', 'bahagia'],
            'fear' => ['scared', 'afraid', 'worried', 'takut', 'khawatir'],
            'love' => ['love', 'romance', 'heart', 'cinta', 'sayang'],
            'anxiety' => ['anxious', 'nervous', 'stress', 'cemas', 'gelisah'],
            'hope' => ['hope', 'optimistic', 'berharap', 'optimis'],
            'excitement' => ['excited', 'thrilled', 'bersemangat', 'seru'],
            'loneliness' => ['lonely', 'alone', 'sendiri', 'kesepian'],
            'gratitude' => ['grateful', 'thankful', 'bersyukur', 'terima kasih']
        ];

        foreach ($moodKeywords as $mood => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    return $mood;
                }
            }
        }

        return 'inspirational';
    }

    /**
     * Get all quotes (with filters)
     */
    public function getAllQuotes(Request $request)
    {
        $quotes = BookQuote::with('book')
            ->when($request->mood, function ($q) use ($request) {
                return $q->where('mood', $request->mood);
            })
            ->when($request->search, function ($q) use ($request) {
                return $q->where('quote', 'like', '%' . $request->search . '%')
                    ->orWhereHas('book', function ($q2) use ($request) {
                        $q2->where('title', 'like', '%' . $request->search . '%');
                    });
            })
            ->latest()
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $quotes]);
    }

    /**
     * Get all user quotes for community page
     */
    public function getAllUserQuotes(Request $request)
    {
        $userId = auth()->id();

        $quotes = BookQuote::with('user')
            ->where('is_approved', true)
            ->when($request->mood, function ($q) use ($request) {
                return $q->where('mood', $request->mood);
            })
            ->when($request->search, function ($q) use ($request) {
                return $q->where('quote', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($quote) use ($userId) {
                // Check if current user liked this quote
                $quote->is_liked = QuoteLike::where('quote_id', $quote->id)
                    ->where('user_id', $userId)
                    ->exists();

                // For anonymous quotes, hide the real author name
                if ($quote->is_anonymous) {
                    $quote->author_name = 'Anonymous';
                    $quote->user = null;
                }

                // Check if current user is the owner
                $quote->is_owner = $quote->user_id === $userId;

                return $quote;
            });
        return response()->json([
            'success' => true,
            'data' => $quotes
        ]);
    }

    /**
     * Get quotes by specific mood
     */
    public function getQuotesByMoodTag($mood)
    {
        $quotes = BookQuote::with('book')
            ->where('mood', $mood)
            ->inRandomOrder()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $quotes,
            'mood' => $mood
        ]);
    }

    /**
     * Save quote to user's collection
     */
    public function saveQuote($id)
    {
        $exists = UserSavedQuote::where('user_id', auth()->id())
            ->where('quote_id', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Quote already saved'
            ], 400);
        }

        UserSavedQuote::create([
            'user_id' => auth()->id(),
            'quote_id' => $id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quote saved successfully'
        ]);
    }

    /**
     * Remove saved quote
     */
    public function unsaveQuote($id)
    {
        UserSavedQuote::where('user_id', auth()->id())
            ->where('quote_id', $id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quote removed from saved'
        ]);
    }

    /**
     * Get user's saved quotes
     */
    public function getSavedQuotes()
    {
        $quotes = UserSavedQuote::where('user_id', auth()->id())
            ->with('quote.book')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $quotes
        ]);
    }

    /**
     * Add user's own quote
     */
    public function addQuote(Request $request)
    {
        $request->validate([
            'quote' => 'required|string',
            'mood' => 'nullable|string',
            'is_anonymous' => 'boolean'
        ]);

        $user = auth()->user();

        $quote = BookQuote::create([
            'book_id' => null,
            'quote' => $request->quote,
            'mood' => $request->mood,
            'user_id' => $user->id,
            'is_approved' => true,
            'author_name' => $request->is_anonymous ? 'Anonymous' : $user->name,
            'likes_count' => 0,
            'is_anonymous' => $request->is_anonymous ?? false
        ]);

        return response()->json([
            'success' => true,
            'data' => $quote,
            'message' => 'Quote added successfully'
        ]);
    }

    /**
     * Delete user's own quote
     */
    public function deleteQuote($id)
    {
        $userId = auth()->id();

        $quote = BookQuote::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Quote not found or you do not have permission to delete it'
            ], 404);
        }

        // Delete related likes first
        QuoteLike::where('quote_id', $id)->delete();

        // Delete the quote
        $quote->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quote deleted successfully'
        ]);
    }

    /**
     * Like or unlike a quote
     */
    public function likeQuote($id)
    {
        $userId = auth()->id();

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to like'
            ], 401);
        }

        $existing = QuoteLike::where('quote_id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            // Unlike
            $existing->delete();
            $liked = false;
        } else {
            // Like
            QuoteLike::create([
                'quote_id' => $id,
                'user_id' => $userId
            ]);
            $liked = true;
        }

        $likesCount = QuoteLike::where('quote_id', $id)->count();
        BookQuote::where('id', $id)->update(['likes_count' => $likesCount]);

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $likesCount
        ]);
    }
}
