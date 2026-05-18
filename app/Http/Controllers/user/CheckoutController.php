<?php
// app/Http/Controllers/Api/CheckoutController.php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\DeliveryAddress;
use App\Models\DeliveryMethod;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    public function getCheckoutData()
    {
        $user = Auth::user();

        // Get user's cart with items
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items->count() == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        $cartItems = $cart->items()->with('book')->get();

        $cartData = $cartItems->map(function ($item) {
            return [
                'id' => $item->book_id,
                'title' => $item->book->title,
                'author' => $item->book->author,
                'price' => $item->book->price,
                'cover_img' => $item->book->cover_img,
                'quantity' => $item->qty,
                'subtotal' => $item->book->price * $item->qty
            ];
        });

        $subtotal = $cartData->sum('subtotal');

        // Get delivery addresses
        $addresses = DeliveryAddress::where('user_id', $user->id)->get();

        // Get delivery methods
        $deliveryMethods = DeliveryMethod::where('is_active', true)->get();

        // Get payment methods
        $paymentMethods = PaymentMethod::get();

        return response()->json([
            'success' => true,
            'data' => [
                'cart_items' => $cartData,
                'subtotal' => $subtotal,
                'addresses' => $addresses,
                'delivery_methods' => $deliveryMethods,
                'payment_methods' => $paymentMethods
            ]
        ]);
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'delivery_method_id' => 'required|exists:delivery_methods,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'delivery_address_id' => 'nullable|exists:delivery_addresses,id',
            'shipping_cost' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0'
        ]);

        $user = Auth::user();

        // Get user's cart
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items->count() == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        $cartItems = $cart->items()->with('book')->get();

        DB::beginTransaction();

        try {
            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'payment_method_id' => $request->payment_method_id,
                'date' => now(),
                'total' => $request->total,
                'status' => Transaction::STATUS_WAITING_PAYMENT,
                'delivery_address_id' => $request->delivery_address_id,
                'delivery_method_id' => $request->delivery_method_id,
                'shipping_cost' => $request->shipping_cost
            ]);

            // Create transaction items
            foreach ($cartItems as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'book_id' => $item->book_id,
                    'quantity' => $item->qty,
                    'price' => $item->book->price,
                    'subtotal' => $item->book->price * $item->qty
                ]);
            }

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'status' => $transaction->status,
                    'total' => $transaction->total
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadPaymentProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = Auth::user();
        $transaction = Transaction::where('user_id', $user->id)->where('id', $id)->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        if ($transaction->status !== Transaction::STATUS_WAITING_PAYMENT) {
            return response()->json([
                'success' => false,
                'message' => 'Payment proof can only be uploaded for waiting payment transactions'
            ], 400);
        }

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $transaction->update([
            'payment_proof' => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment proof uploaded successfully',
            'data' => [
                'payment_proof_url' => $path
            ]
        ]);
    }

    public function getUserTransactions()
    {
        $user = Auth::user();

        $transactions = Transaction::where('user_id', $user->id)
            ->with(['items.book', 'deliveryMethod', 'paymentMethod', 'deliveryAddress'])
            ->orderBy('date', 'desc')
            ->get();

        $transactionsData = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'date' => $transaction->date,
                'total' => $transaction->total,
                'status' => $transaction->status,
                'status_label' => Transaction::getStatuses()[$transaction->status],
                'shipping_cost' => $transaction->shipping_cost,
                'payment_proof' => $transaction->payment_proof ? Storage::url($transaction->payment_proof) : null,
                'delivery_method' => $transaction->deliveryMethod ? [
                    'name' => $transaction->deliveryMethod->name,
                    'estimated_days' => $transaction->deliveryMethod->estimated_days
                ] : null,
                'payment_method' => $transaction->paymentMethod ? [
                    'name' => $transaction->paymentMethod->name
                ] : null,
                'delivery_address' => $transaction->deliveryAddress ? [
                    'full_address' => $transaction->deliveryAddress->full_address
                ] : null,
                'items' => $transaction->items->map(function ($item) {
                    return [
                        'title' => $item->book->title,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                        'cover_img' => $item->book->cover_img
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $transactionsData
        ]);
    }

    public function getTransactionDetail($id)
    {
        $user = Auth::user();

        $transaction = Transaction::where('user_id', $user->id)
            ->with(['items.book', 'deliveryMethod', 'paymentMethod', 'deliveryAddress'])
            ->where('id', $id)
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transaction->id,
                'date' => $transaction->date,
                'total' => $transaction->total,
                'status' => $transaction->status,
                'status_label' => Transaction::getStatuses()[$transaction->status],
                'shipping_cost' => $transaction->shipping_cost,
                'payment_proof' => $transaction->payment_proof ? Storage::url($transaction->payment_proof) : null,
                'delivery_method' => $transaction->deliveryMethod,
                'payment_method' => $transaction->paymentMethod,
                'delivery_address' => $transaction->deliveryAddress,
                'items' => $transaction->items->map(function ($item) {
                    return [
                        'id' => $item->book_id,
                        'title' => $item->book->title,
                        'author' => $item->book->author,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                        'cover_img' => $item->book->cover_img
                    ];
                })
            ]
        ]);
    }
}
