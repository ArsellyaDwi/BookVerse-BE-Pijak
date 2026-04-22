@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Payment Method Details" />

    <div class="space-y-6">
        <!-- Payment Method Information -->
        <x-common.component-card title="Payment Method Information" desc="Details about the payment method">
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method Name</label>
                        <div class="flex items-center gap-3 mt-2">
                            @if($paymentMethod->image)
                                <img src="{{ Storage::url($paymentMethod->image) }}" 
                                     alt="{{ $paymentMethod->name }}" 
                                     class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M6 20h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <p class="text-xl font-semibold text-gray-800 dark:text-white/90">{{ $paymentMethod->name }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                        <p class="mt-2 text-gray-800 dark:text-white/90">{{ $paymentMethod->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</label>
                        <p class="mt-2 text-gray-800 dark:text-white/90">{{ $paymentMethod->updated_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Transactions</label>
                        <p class="mt-2">
                            <span class="inline-flex px-2 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                {{ $paymentMethod->transactions()->count() }} transactions
                            </span>
                        </p>
                    </div>
                </div>
                
                @if($paymentMethod->description)
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                    <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $paymentMethod->description }}</p>
                </div>
                @endif
            </div>
        </x-common.component-card>

        <!-- Transactions using this Payment Method -->
        @if($paymentMethod->transactions->count() > 0)
        <x-common.component-card title="Transactions" desc="All transactions using {{ $paymentMethod->name }}">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-gray-200 border-y dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Transaction ID</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Amount</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($paymentMethod->transactions as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">#{{ $transaction->id }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($transaction->amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    {{ $transaction->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->created_at->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-common.component-card>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.payment-methods.edit', $paymentMethod) }}" 
                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Edit Payment Method
            </a>
            <a href="{{ route('admin.payment-methods.index') }}" 
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection