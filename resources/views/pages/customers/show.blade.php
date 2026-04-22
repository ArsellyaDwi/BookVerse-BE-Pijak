@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Customer Details" />

    <div class="space-y-6">
        <!-- Customer Information -->
        <x-common.component-card title="Customer Information" desc="Personal details">
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer ID</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">#{{ $customer->id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white font-medium">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <p class="text-gray-800 dark:text-white/90">{{ $customer->name }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $customer->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $customer->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </x-common.component-card>

        <!-- Customer Statistics -->
        <x-common.component-card title="Customer Statistics" desc="Order history summary">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalOrders }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Orders</p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $completedOrders }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Completed Orders</p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Spent</p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Average Order</p>
                </div>
            </div>
        </x-common.component-card>

        <!-- Recent Transactions -->
        @if($customer->transactions->count() > 0)
        <x-common.component-card title="Recent Transactions" desc="Customer order history">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-gray-200 border-y dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Transaction ID</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Date</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Items</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Total</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Delivery</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($customer->transactions->take(10) as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">#{{ $transaction->id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $transaction->date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $transaction->items->sum('quantity') }} items</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                @if($transaction->shipping_cost > 0)
                                    <div class="text-xs text-gray-500">+ shipping Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($transaction->deliveryAddress)
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">Delivery</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="text-xs text-green-600 dark:text-green-400">Pickup</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'waiting_payment' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                        'shipped' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                        'done' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$transaction->status] }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($customer->transactions->count() > 10)
            <div class="mt-4 text-center">
                <a href="{{ route('admin.transactions.index', ['search' => $customer->email]) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                    View all {{ $customer->transactions->count() }} transactions →
                </a>
            </div>
            @endif
        </x-common.component-card>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.customers.edit', $customer) }}" 
                class="rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-green-600">
                Edit Customer
            </a>
            @if($customer->transactions()->count() == 0)
            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" 
                  onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600">
                    Delete Customer
                </button>
            </form>
            @endif
            <a href="{{ route('admin.customers.index') }}" 
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection