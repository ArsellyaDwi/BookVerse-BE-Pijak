<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\DeliveryAddress;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::with('user', 'paymentMethod', 'items', 'deliveryAddress');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('date', 'desc')->paginate(15);
        $transactions->appends($request->all());

        // Get statistics
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::where('status', '!=', 'cancelled')->sum('total');
        $averageOrderValue = Transaction::where('status', '!=', 'cancelled')->avg('total') ?? 0;
        $pendingPayments = Transaction::where('status', 'waiting_payment')->count();

        return view('pages.transactions.index', compact('transactions', 'totalTransactions', 'totalRevenue', 'averageOrderValue', 'pendingPayments'));
    }
    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(Transaction $transaction)
    {
        $transaction->load('user', 'paymentMethod', 'deliveryAddress', 'items.book');
        $paymentMethods = PaymentMethod::all();
        $statuses = Transaction::getStatuses();

        return view('pages.transactions.edit', compact('transaction', 'paymentMethods', 'statuses'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:waiting_payment,shipped,done,cancelled',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            if ($transaction->payment_proof) {
                Storage::disk('public')->delete($transaction->payment_proof);
            }
            $validated['payment_proof'] = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        $transaction->update([
            'status' => $validated['status'],
            'payment_method_id' => $validated['payment_method_id'],
            'payment_proof' => $validated['payment_proof'] ?? $transaction->payment_proof,
        ]);

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaction updated successfully!');
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'paymentMethod', 'deliveryAddress', 'items.book');
        $storeSettings = StoreSetting::first();
        return view('pages.transactions.show', compact('transaction', 'storeSettings'));
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Transaction $transaction)
    {
        if ($transaction->payment_proof) {
            Storage::disk('public')->delete($transaction->payment_proof);
        }

        $transaction->delete();

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaction deleted successfully!');
    }

    /**
     * Generate invoice for transaction
     */
    public function invoice(Transaction $transaction)
    {
        $transaction->load('user', 'paymentMethod', 'deliveryAddress', 'items.book');
        $storeSettings = StoreSetting::first();

        return view('pages.transactions.invoice', compact('transaction', 'storeSettings'));
    }

    /**
     * Update transaction status
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:waiting_payment,shipped,done,cancelled',
        ]);

        $transaction->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
}
