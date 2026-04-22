<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMethod;
use Illuminate\Http\Request;

class DeliveryMethodController extends Controller
{
    /**
     * Display a listing of delivery methods.
     */
    public function index(Request $request)
    {
        $query = DeliveryMethod::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $deliveryMethods = $query->orderBy('name')->paginate(10);
        $deliveryMethods->appends($request->all());

        return view('pages.delivery-methods.index', compact('deliveryMethods'));
    }

    /**
     * Show the form for creating a new delivery method.
     */
    public function create()
    {
        return view('pages.delivery-methods.create');
    }

    /**
     * Store a newly created delivery method in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'books_per_multiplier' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'estimated_days_min' => 'nullable|integer|min:1',
            'estimated_days_max' => 'nullable|integer|min:1|gte:estimated_days_min',
        ]);

        $validated['is_active'] = $request->has('is_active');

        DeliveryMethod::create($validated);

        return redirect()->route('admin.delivery-methods.index')
            ->with('success', 'Delivery method created successfully!');
    }

    /**
     * Display the specified delivery method.
     */
    public function show(DeliveryMethod $deliveryMethod)
    {
        return view('pages.delivery-methods.show', compact('deliveryMethod'));
    }

    /**
     * Show the form for editing the specified delivery method.
     */
    public function edit(DeliveryMethod $deliveryMethod)
    {
        return view('pages.delivery-methods.edit', compact('deliveryMethod'));
    }

    /**
     * Update the specified delivery method in storage.
     */
    public function update(Request $request, DeliveryMethod $deliveryMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'books_per_multiplier' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'estimated_days_min' => 'nullable|integer|min:1',
            'estimated_days_max' => 'nullable|integer|min:1|gte:estimated_days_min',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $deliveryMethod->update($validated);

        return redirect()->route('admin.delivery-methods.index')
            ->with('success', 'Delivery method updated successfully!');
    }

    /**
     * Remove the specified delivery method from storage.
     */
    public function destroy(DeliveryMethod $deliveryMethod)
    {
        // Check if delivery method is used in any transaction
        if ($deliveryMethod->transactions()->count() > 0) {
            return redirect()->route('admin.delivery-methods.index')
                ->with('error', 'Cannot delete delivery method because it is associated with transactions.');
        }

        $deliveryMethod->delete();

        return redirect()->route('admin.delivery-methods.index')
            ->with('success', 'Delivery method deleted successfully!');
    }

    /**
     * Toggle delivery method status.
     */
    public function toggleStatus(DeliveryMethod $deliveryMethod)
    {
        $deliveryMethod->update(['is_active' => !$deliveryMethod->is_active]);

        $status = $deliveryMethod->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.delivery-methods.index')
            ->with('success', "Delivery method {$status} successfully!");
    }
}
