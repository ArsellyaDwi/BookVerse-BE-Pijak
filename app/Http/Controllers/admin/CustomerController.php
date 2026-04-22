<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->where('role', '=', 'customer')->orderBy('created_at', 'desc')->paginate(15);
        $customers->appends($request->all());

        // Get statistics
        $totalCustomers = User::where('role', '=', 'customer')->count();
        $newCustomersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('role', '=', 'customer')
            ->count();

        return view('pages.customers.index', compact('customers', 'totalCustomers', 'newCustomersThisMonth'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('pages.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified customer.
     */
    public function show(User $customer)
    {
        $customer->load('transactions', 'recommendations');

        // Get customer statistics
        $totalSpent = $customer->transactions()->where('status', '!=', 'cancelled')->sum('total');
        $totalOrders = $customer->transactions()->count();
        $completedOrders = $customer->transactions()->where('status', 'done')->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;

        return view('pages.customers.show', compact('customer', 'totalSpent', 'totalOrders', 'completedOrders', 'averageOrderValue'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(User $customer)
    {
        return view('pages.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Handle password update
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully!');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(User $customer)
    {
        // Check if customer has transactions
        if ($customer->transactions()->count() > 0) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Cannot delete customer because they have transaction history.');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully!');
    }
}
