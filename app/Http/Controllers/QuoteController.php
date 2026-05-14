<?php

namespace App\Http\Controllers;

use App\Models\BookQuote;
use App\Models\UserSavedQuote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function getQuoteOfDay()
    {
        $quote = BookQuote::with('book')->inRandomOrder()->first();
        
        return response()->json([
            'success' => true,
            'data' => $quote
        ]);
    }
    
    public function getAllQuotes(Request $request)
    {
        $quotes = BookQuote::with('book')
            ->when($request->mood, function($q) use ($request) {
                return $q->where('mood', $request->mood);
            })
            ->when($request->search, function($q) use ($request) {
                return $q->where('quote', 'like', '%'.$request->search.'%')
                         ->orWhereHas('book', function($q2) use ($request) {
                             $q2->where('title', 'like', '%'.$request->search.'%');
                         });
            })
            ->latest()
            ->paginate(20);
            
        return response()->json(['success' => true, 'data' => $quotes]);
    }
    
    public function saveQuote($id)
    {
        UserSavedQuote::create([
            'user_id' => auth()->id(),
            'quote_id' => $id
        ]);
        
        return response()->json(['success' => true]);
    }
}