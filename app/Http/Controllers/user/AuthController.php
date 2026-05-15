<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string|max:100',
            'province' => 'sometimes|string|max:100',
            'postal_code' => 'sometimes|string|max:10',
            'gender' => 'sometimes|in:male,female,other',
            'birth_date' => 'sometimes|date|before:today',
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

        return response()->json([
            'success' => true,
            'has_completed' => $hasCompleted,
            'personality' => $hasCompleted ? [
                'extroversion' => (float) $user->extroversion,
                'neuroticism' => (float) $user->neuroticism,
                'agreeableness' => (float) $user->agreeableness,
                'conscientiousness' => (float) $user->conscientiousness,
                'openness' => (float) $user->openness,
            ] : null
        ]);
    }

    public function savePersonality(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'extroversion' => 'required|numeric|min:0|max:100',
            'neuroticism' => 'required|numeric|min:0|max:100',
            'agreeableness' => 'required|numeric|min:0|max:100',
            'conscientiousness' => 'required|numeric|min:0|max:100',
            'openness' => 'required|numeric|min:0|max:100',
        ]);

        $user->update([
            'extroversion' => $request->extroversion,
            'neuroticism' => $request->neuroticism,
            'agreeableness' => $request->agreeableness,
            'conscientiousness' => $request->conscientiousness,
            'openness' => $request->openness,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Personality saved successfully'
        ]);
    }
}
