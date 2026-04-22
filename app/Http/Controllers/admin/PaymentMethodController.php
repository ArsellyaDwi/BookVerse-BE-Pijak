<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the payment methods.
     */
    public function index(Request $request)
    {
        $query = PaymentMethod::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $paymentMethods = $query->orderBy('name')->paginate(10);
        $paymentMethods->appends(['search' => $request->search]);

        return view('pages.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create()
    {
        return view('pages.payment-methods.create');
    }

    /**
     * Store a newly created payment method in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('payment-methods', 'public');
        }

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method created successfully!');
    }

    /**
     * Display the specified payment method.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        $paymentMethod->load('transactions');
        return view('pages.payment-methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('pages.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name,' . $paymentMethod->id,
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($paymentMethod->image) {
                Storage::disk('public')->delete($paymentMethod->image);
            }
            $validated['image'] = $request->file('image')->store('payment-methods', 'public');
        }

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method updated successfully!');
    }

    /**
     * Remove the specified payment method from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Check if payment method has transactions
        if ($paymentMethod->transactions()->count() > 0) {
            return redirect()->route('admin.payment-methods.index')
                ->with('error', 'Cannot delete payment method because it is associated with transactions.');
        }

        // Delete image if exists
        if ($paymentMethod->image) {
            Storage::disk('public')->delete($paymentMethod->image);
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method deleted successfully!');
    }
}
