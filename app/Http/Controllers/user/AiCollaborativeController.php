<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AiCollaborativeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user('api');

            if ($user) {

                $user_id = $user->id;

                $response = Http::withHeader('X-API-Key', env('AI_SERVICE_KEY'))
                    ->withQueryParameters([
                        'user_id' => $user_id,
                    ])
                    ->get(env('AI_SERVICE_URL') . '/collaborative/recommend');


                if ($response->successful()) {
                    $aiResult = $response->json();

                    $recommendedBookIds = collect($aiResult['recommendations'])->pluck('book_id')->toArray();
                    if (len($recommendedBookIds) > 0) {
                        $books = Book::whereIn('id', $recommendedBookIds)
                            ->orderByRaw(DB::raw("FIELD(id, " . implode(',', $recommendedBookIds) . ")"))
                            ->get();

                        return response()->json([
                            'success' => true,
                            'data' => $books,
                            'message' => "Success",
                        ]);
                    } else {
                        return response()->json([
                            'success' => true,
                            'data' => [],
                            'message' => "Success",
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to connect to AI service: ' . $response,
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => "Empty result because user not logged in",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to AI service: ' . $e->getMessage()
            ], 500);
        }
    }
}
