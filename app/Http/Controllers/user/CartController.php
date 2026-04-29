<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::guard('api')->user()->id;
        
        // FIX: This should be Cart, not Wishlist
        $cart = Cart::with(['items.book'])->where('user_id', $userId)->first();
        
        if (!$cart) {
            return response()->json([
                'data' => []
            ]);
        }
        
        return response()->json([
            'data' => $cart->items
        ]);
    }

    /**
     * Store a newly created resource in storage. (ADD / PLUS CART)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'qty' => 'required|integer|min:1'
        ]);

        $userId = Auth::guard('api')->user()->id;

        $cart = Cart::firstOrCreate([
            'user_id' => $userId,
        ]);

        $book = Book::find($validated['book_id']);

        if ($book->stock < $validated['qty']) {
            return response()->json([
                'message' => 'Stok tidak mencukupi',
            ], 400);
        }

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('book_id', $validated['book_id'])
            ->first();

        if ($existingItem) {
            $newQty = $existingItem->qty + $validated['qty'];

            if ($book->stock < $newQty) {
                return response()->json([
                    'message' => 'Jumlah melebihi stok',
                ], 400);
            }

            $existingItem->update([
                'qty' => $newQty,
            ]);
            
            // Load book relationship
            $existingItem->load('book');

            return response()->json([
                'message' => 'Quantity berhasil diupdate',
                'data' => $existingItem,
            ]);
        }

        $item = CartItem::create([
            'cart_id' => $cart->id,
            'book_id' => $validated['book_id'],
            'qty' => $validated['qty'],
        ]);
        
        // Load book relationship
        $item->load('book');

        return response()->json([
            'message' => 'Berhasil ditambahkan ke cart',
            'data' => $item,
        ], 201);
    }

    /**
     * MINUS CART - Decrease quantity or remove item
     */
    public function minus(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'qty' => 'required|integer|min:1'
        ]);

        $userId = Auth::guard('api')->user()->id;

        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart tidak ditemukan',
            ], 404);
        }

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('book_id', $validated['book_id'])
            ->first();

        if (!$existingItem) {
            return response()->json([
                'message' => 'Item tidak ditemukan di cart',
            ], 404);
        }

        $newQty = $existingItem->qty - $validated['qty'];

        if ($newQty <= 0) {
            // Remove item if quantity becomes 0 or less
            $existingItem->delete();
            
            return response()->json([
                'message' => 'Item berhasil dihapus dari cart',
                'removed' => true
            ]);
        }

        // Update with new quantity
        $existingItem->update([
            'qty' => $newQty,
        ]);
        
        // Load book relationship
        $existingItem->load('book');

        return response()->json([
            'message' => 'Quantity berhasil dikurangi',
            'data' => $existingItem,
            'new_qty' => $newQty
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userId = Auth::guard('api')->user()->id;

        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart tidak ditemukan',
            ], 404);
        }

        $item = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->first();

        if (!$item) {
            return response()->json([
                'message' => 'Item tidak ditemukan',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'message' => 'Item berhasil dihapus dari cart',
        ]);
    }
    
    /**
     * Remove by book_id (alternative method)
     */
    public function removeByBookId(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $userId = Auth::guard('api')->user()->id;

        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart tidak ditemukan',
            ], 404);
        }

        $deleted = CartItem::where('cart_id', $cart->id)
            ->where('book_id', $validated['book_id'])
            ->delete();

        if (!$deleted) {
            return response()->json([
                'message' => 'Item tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Item berhasil dihapus dari cart',
        ]);
    }
}