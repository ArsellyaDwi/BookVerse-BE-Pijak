<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReplyMail;  // Tambahkan ini

class ContactMessageController extends Controller
{
    /**
     * Display list of contact messages
     */
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(20);
        $unreadCount = ContactMessage::where('status', 'unread')->count();
        
        return view('pages.contact-messages.index', compact('messages', 'unreadCount'));
    }

    /**
     * Show single message detail
     */
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        // Mark as read if unread
        if ($message->status === 'unread') {
            $message->update(['status' => 'read']);
        }
        
        return view('pages.contact-messages.show', compact('message'));
    }

    /**
     * Reply to message and send email to customer
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|min:3',
        ]);

        $message = ContactMessage::findOrFail($id);
        
        // Update reply di database
        $message->update([
            'status' => 'replied',
            'admin_reply' => $request->reply,
            'replied_at' => now(),
        ]);

        // KIRIM EMAIL KE CUSTOMER
        try {
            Mail::to($message->email)->send(new ContactReplyMail($message, $request->reply));
            
            return redirect()->route('admin.contact-messages.index')
                ->with('success', 'Reply sent successfully! Email has been sent to ' . $message->email);
        } catch (\Exception $e) {
            return redirect()->route('admin.contact-messages.index')
                ->with('warning', 'Reply saved but email failed to send. Error: ' . $e->getMessage());
        }
    }

    /**
     * Delete message
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Message deleted successfully!');
    }

    /**
     * Bulk delete messages
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_messages,id',
        ]);

        ContactMessage::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Resend email reply to customer (Tambahan)
     */
    public function resendEmail($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        if (!$message->admin_reply) {
            return redirect()->back()
                ->with('error', 'No reply found to resend.');
        }

        try {
            Mail::to($message->email)->send(new ContactReplyMail($message, $message->admin_reply));
            
            return redirect()->back()
                ->with('success', 'Email resent successfully to ' . $message->email);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to resend email: ' . $e->getMessage());
        }
    }
}