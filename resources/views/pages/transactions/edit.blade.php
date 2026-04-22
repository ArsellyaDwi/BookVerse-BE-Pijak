@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Edit Transaction" />

    <x-common.component-card title="Edit Transaction" desc="Update transaction details">
        <form action="{{ route('admin.transactions.update', $transaction) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <!-- Transaction ID (Read-only) -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Transaction ID
                    </label>
                    <input type="text" 
                           value="#{{ $transaction->id }}"
                           disabled
                           class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                </div>

                <!-- Customer Info (Read-only) -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Customer
                    </label>
                    <input type="text" 
                           value="{{ $transaction->user->name }} ({{ $transaction->user->email }})"
                           disabled
                           class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                </div>

                <!-- Status -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Status <span class="text-error-500">*</span>
                    </label>
                    <select name="status" 
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('status') border-error-300 @enderror">
                        <option value="waiting_payment" {{ old('status', $transaction->status) == 'waiting_payment' ? 'selected' : '' }}>Waiting Payment</option>
                        <option value="shipped" {{ old('status', $transaction->status) == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="done" {{ old('status', $transaction->status) == 'done' ? 'selected' : '' }}>Done</option>
                        <option value="cancelled" {{ old('status', $transaction->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    
                    @error('status')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Payment Method <span class="text-error-500">*</span>
                    </label>
                    <select name="payment_method_id" 
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('payment_method_id') border-error-300 @enderror">
                        <option value="">Select Payment Method</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->id }}" {{ old('payment_method_id', $transaction->payment_method_id) == $method->id ? 'selected' : '' }}>
                                {{ $method->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    @error('payment_method_id')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Delivery Address (Read-only Display) -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Delivery Method
                    </label>
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-800">
                        @if($transaction->deliveryAddress)
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Delivery to Address</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $transaction->deliveryAddress->full_address }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-green-600 dark:text-green-400">Pickup at Store</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Customer will pick up the order at the store</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <p class="text-theme-xs text-gray-500 mt-1.5">Delivery address cannot be changed here. Contact customer if address needs update.</p>
                </div>

                <!-- Payment Proof -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Payment Proof
                    </label>
                    
                    @if($transaction->payment_proof)
                        <div class="mb-3">
                            <label class="text-sm text-gray-500 dark:text-gray-400">Current Payment Proof</label>
                            <div class="mt-2">
                                @php
                                    $extension = pathinfo($transaction->payment_proof, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'webp']))
                                    <img src="{{ Storage::url($transaction->payment_proof) }}" 
                                         alt="Payment Proof" 
                                         class="max-w-xs rounded-lg border border-gray-200 dark:border-gray-700">
                                @else
                                    <a href="{{ Storage::url($transaction->payment_proof) }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        View Current Payment Proof
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <input type="file" 
                           name="payment_proof" 
                           accept="image/jpeg,image/png,image/jpg,application/pdf"
                           class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:file:bg-gray-800 dark:file:text-gray-300">
                    
                    @error('payment_proof')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                    
                    <p class="text-theme-xs text-gray-500 mt-1.5">Upload JPG, PNG, or PDF (max 2MB)</p>
                </div>

                <!-- Order Summary (Read-only) -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Order Summary
                    </label>
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Book</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Qty</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Price</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($transaction->items as $item)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $item->book->title }}</td>
                                    <td class="px-4 py-2 text-center text-sm text-gray-700 dark:text-gray-300">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2 text-right text-sm text-gray-700 dark:text-gray-300">${{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-2 text-right text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-right text-sm font-medium text-gray-700 dark:text-gray-300">Total:</td>
                                    <td class="px-4 py-2 text-right text-sm font-bold text-gray-900 dark:text-white">${{ number_format($transaction->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.transactions.index') }}" 
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Update Transaction
                    </button>
                </div>
            </div>
        </form>
    </x-common.component-card>
</div>
@endsection