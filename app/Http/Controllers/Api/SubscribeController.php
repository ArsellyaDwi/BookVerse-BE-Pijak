<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SubscribeController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $subscriber = Subscriber::create([
            'email' => $request->email,
            'is_active' => true,
        ]);

        // Kirim email welcome (opsional)
        try {
            Mail::send('emails.welcome-subscriber', ['email' => $request->email], function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Welcome to BookVerse Newsletter!');
            });
        } catch (\Exception $e) {
            \Log::error('Subscribe email failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to newsletter!'
        ]);
    }
}