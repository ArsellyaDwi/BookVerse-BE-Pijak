<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $contact = Contact::create($request->all());

        try {
            Mail::raw(
                "Name: {$request->name}\n" .
                "Email: {$request->email}\n" .
                "Subject: {$request->subject}\n\n" .
                "Message:\n{$request->message}",
                function ($message) use ($request) {
                    $message->to('admin@bookverse.com')
                            ->subject('New Contact Message: ' . $request->subject);
                }
            );
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $contact
        ], 200);
    }

    public function index()
    {
        $messages = Contact::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function show($id)
    {
        $message = Contact::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    public function markAsRead($id)
    {
        $message = Contact::findOrFail($id);
        $message->update(['is_read' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'Message marked as read'
        ]);
    }

    public function destroy($id)
    {
        $message = Contact::findOrFail($id);
        $message->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }
}