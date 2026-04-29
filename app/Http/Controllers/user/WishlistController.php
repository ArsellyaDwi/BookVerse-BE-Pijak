<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index() {
        $userId = Auth::guard('api')->user()->id;
        $data = Wishlist::with('items', 'items.book')->where('user_id', '=', $userId)->first();
        return response()->json([
            'data' => $data ? $data->items : [],
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'book_id' => 'required',
        ]);

        $userId = Auth::guard('api')->user()->id;
        
        $wishlist = Wishlist::where('user_id', '=', $userId)->first();
        if (!$wishlist) {
            $wishlist = Wishlist::create([
                'user_id' => $userId,
            ]);
        }

        // Refresh wishlist items relationship
        $wishlist->load('items');
        
        if ($wishlist->items->where('book_id', '=', $validated['book_id'])->first()) {
            return response()->json([
                'message' => 'Buku sudah ditambahkan ke wishlist sebelumnya.',
            ]);
        }

        $item = WishlistItem::create([
            'wishlist_id' => $wishlist->id,
            'book_id' => $validated['book_id'],
        ]);

        return response()->json([
            'data' => $item,
        ]);
    }

    public function destroy(string $id) {
        $userId = Auth::guard('api')->user()->id;
        $wishlist = Wishlist::where('user_id', '=', $userId)->first();
        
        if (!$wishlist) {
            return response()->json([
                'message' => 'Wishlist not found',
            ], 404);
        }
        
        $item = WishlistItem::where('id', '=', $id)
            ->where('wishlist_id', '=', $wishlist->id)
            ->delete();

        return response()->json([
            'message' => 'Success',
        ]);
    }
}