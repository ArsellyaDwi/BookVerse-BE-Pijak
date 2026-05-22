<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => auth('api')->user(),
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $token = auth('api')->attempt([
            'email' => $user->email,
            'password' => $request->password,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function me()
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function updateAccount(Request $request)
    {
        $user = auth('api')->user();
        $user->update($request->only(['name', 'email']));

        return response()->json([
            'message' => 'Account updated successfully',
            'user' => $user,
        ]);
    }
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'gender' => 'nullable|in:Male,Female,Other',
            'birth_date' => 'nullable|date|before:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only([
            'name',
            'phone',
            'address',
            'city',
            'province',
            'postal_code',
            'gender',
            'birth_date'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth('api')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    public function getPersonalityStatus(Request $request)
    {
        $user = $request->user();

        $hasCompleted = !is_null($user->extroversion) &&
            !is_null($user->neuroticism) &&
            !is_null($user->agreeableness) &&
            !is_null($user->conscientiousness) &&
            !is_null($user->openness);

        $genres = [];
        if ($hasCompleted) {
            $genres = $this->getGenreRecommendations([
                'openness' => (float) $user->openness,
                'conscientiousness' => (float) $user->conscientiousness,
                'extroversion' => (float) $user->extroversion,
                'agreeableness' => (float) $user->agreeableness,
                'neuroticism' => (float) $user->neuroticism,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'has_completed' => $hasCompleted,
                'personality' => $hasCompleted ? [
                    'extroversion' => (float) $user->extroversion,
                    'neuroticism' => (float) $user->neuroticism,
                    'agreeableness' => (float) $user->agreeableness,
                    'conscientiousness' => (float) $user->conscientiousness,
                    'openness' => (float) $user->openness,
                ] : null,
                'genres' => $genres
            ],
        ]);
    }

    private function getGenreRecommendations($personality)
    {
        // HANYA ambil genre yang memiliki buku (join dengan book_genre)
        $genres = DB::table('genres')
            ->join('book_genre', 'genres.id', '=', 'book_genre.genre_id')
            ->select(
                'genres.id',
                'genres.name',
                'genres.openness',
                'genres.conscientiousness',
                'genres.extroversion',
                'genres.agreeableness',
                'genres.neuroticism',
                DB::raw('COUNT(book_genre.book_id) as total_books')
            )
            ->groupBy(
                'genres.id',
                'genres.name',
                'genres.openness',
                'genres.conscientiousness',
                'genres.extroversion',
                'genres.agreeableness',
                'genres.neuroticism'
            )
            ->having('total_books', '>', 0)
            ->get();

        if ($genres->isEmpty()) {
            return [];
        }

        $recommendations = [];
        $maxDistance = sqrt(5 * 100 * 100);

        foreach ($genres as $genre) {
            // Hitung similarity
            $totalDiff = pow($personality['extroversion'] - $genre->extroversion, 2)
                + pow($personality['neuroticism'] - $genre->neuroticism, 2)
                + pow($personality['agreeableness'] - $genre->agreeableness, 2)
                + pow($personality['conscientiousness'] - $genre->conscientiousness, 2)
                + pow($personality['openness'] - $genre->openness, 2);

            $euclideanDistance = sqrt($totalDiff);
            $similarity = (1 - ($euclideanDistance / $maxDistance)) * 100;
            $similarity = round($similarity, 2);

            $recommendations[] = [
                'id' => $genre->id,
                'genre' => $genre->name,
                'score' => $similarity,
                'total_books' => $genre->total_books
            ];
        }

        // Urutkan berdasarkan score tertinggi
        usort($recommendations, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Ambil 5 genre teratas yang PUNYA BUKU
        return array_slice($recommendations, 0, 5);
    }

    private function calculateSimilarity($user, $genre)
    {
        $totalDiff = 0;
        $traits = ['extroversion', 'neuroticism', 'agreeableness', 'conscientiousness', 'openness'];

        foreach ($traits as $trait) {
            $diff = ($user[$trait] - $genre[$trait]);
            $totalDiff += $diff * $diff;
        }

        $euclideanDistance = sqrt($totalDiff);
        $maxPossibleDistance = sqrt(5 * 100 * 100);
        $similarity = (1 - ($euclideanDistance / $maxPossibleDistance)) * 100;

        return max(0, min(100, $similarity));
    }

    public function savePersonality(Request $request)
    {
        $user = $request->user();


        try {
            $user = $request->user('api');

            $response = Http::withHeader('X-API-Key', env('AI_SERVICE_KEY'))
                ->post(
                    env('AI_SERVICE_URL') . '/personality/predict',
                    $request->data,
                );


            if ($response->successful()) {
                $aiResult = $response->json();

                $user->update([
                    'extroversion' => $aiResult['extroversion'],
                    'neuroticism' => $aiResult['neuroticism'],
                    'agreeableness' => $aiResult['agreeableness'],
                    'conscientiousness' => $aiResult['conscientiousness'],
                    'openness' => $aiResult['openness'],
                ]);

                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => "Success",
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to connect to AI service: ' . $response,
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to AI service: ' . $e->getMessage()
            ], 500);
        }
    }
}
