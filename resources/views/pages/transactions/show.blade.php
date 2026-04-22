@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Transaction Details" />

    <div class="space-y-6">
        <!-- Transaction Information -->
        <x-common.component-card title="Transaction Information" desc="Order details and customer information">
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Transaction ID</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">#{{ $transaction->id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        <p class="mt-1">
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
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</label>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white/90">{{ $transaction->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $transaction->date->format('F d, Y h:i:s A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</label>
                        <div class="flex items-center gap-2 mt-1">
                            @if($transaction->paymentMethod && $transaction->paymentMethod->image)
                                <img src="{{ Storage::url($transaction->paymentMethod->image) }}" 
                                     alt="{{ $transaction->paymentMethod->name }}" 
                                     class="w-6 h-6 object-cover rounded">
                            @endif
                            <p class="text-gray-800 dark:text-white/90">{{ $transaction->paymentMethod->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Subtotal</label>
                        <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                    </div>
                    @if($transaction->shipping_cost > 0)
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Shipping Cost</label>
                        <p class="mt-1 text-xl font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Grand Total</label>
                        <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($transaction->total + $transaction->shipping_cost, 0, ',', '.') }}</p>
                    </div>
                    @else
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</label>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                    </div>
                    @endif
                </div>
                
                <!-- Delivery Information -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Delivery Information</label>
                    <div class="mt-2 p-4 rounded-lg {{ $transaction->deliveryAddress ? 'bg-blue-50 dark:bg-blue-900/20' : 'bg-green-50 dark:bg-green-900/20' }}">
                        @if($transaction->deliveryAddress)
                            <div class="flex flex-col gap-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">Delivery to Address</p>
                                        @if($transaction->deliveryMethod)
                                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                                Method: {{ $transaction->deliveryMethod->name }}
                                                @if($transaction->deliveryMethod->estimated_days_min && $transaction->deliveryMethod->estimated_days_max)
                                                    (Est. {{ $transaction->deliveryMethod->estimated_days_min }}-{{ $transaction->deliveryMethod->estimated_days_max }} days)
                                                @endif
                                            </p>
                                        @endif
                                        <div class="mt-2 space-y-1">
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                <span class="font-medium">Province:</span> {{ $transaction->deliveryAddress->province }}
                                            </p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                <span class="font-medium">City:</span> {{ $transaction->deliveryAddress->city }}
                                            </p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                <span class="font-medium">District:</span> {{ $transaction->deliveryAddress->district }}
                                            </p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                <span class="font-medium">Village:</span> {{ $transaction->deliveryAddress->village }}
                                            </p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                <span class="font-medium">Address:</span> {{ $transaction->deliveryAddress->address }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Leaflet Map for Coordinates -->
                                @if($transaction->deliveryAddress->lat && $transaction->deliveryAddress->long)
                                <div class="border-t border-blue-200 dark:border-blue-800 pt-3 mt-2">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="font-medium">📍 Location Map:</span> 
                                        ({{ $transaction->deliveryAddress->lat }}, {{ $transaction->deliveryAddress->long }})
                                    </p>
                                    <div id="deliveryMap" class="h-96! w-full overflow-hidden" 
                                         data-lat="{{ $transaction->deliveryAddress->lat }}" 
                                         data-lng="{{ $transaction->deliveryAddress->long }}"
                                         data-address="{{ $transaction->deliveryAddress->address }}, {{ $transaction->deliveryAddress->village }}, {{ $transaction->deliveryAddress->district }}, {{ $transaction->deliveryAddress->city }}, {{ $transaction->deliveryAddress->province }}">
                                    </div>
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-green-800 dark:text-green-300">Pickup at Store</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Customer will pick up the order at the store location</p>
                                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">No shipping cost</p>
                                    @php
                                        $storeSettings = \App\Models\StoreSetting::first();
                                    @endphp
                                    @if($storeSettings)
                                        <div class="mt-3 pt-3 border-t border-green-200 dark:border-green-800">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Store Information:</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $storeSettings->store_name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $storeSettings->store_address }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Contact: {{ $storeSettings->store_contact }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Payment Proof -->
                @if($transaction->payment_proof)
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Proof</label>
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
                                View Payment Proof
                            </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </x-common.component-card>

        <!-- Order Items -->
        <x-common.component-card title="Order Items" desc="Products in this order">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-gray-200 border-y dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">#</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Book Title</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Author</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Quantity</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Price</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($transaction->items as $index => $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.books.show', $item->book) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                    {{ $item->book->title }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $item->book->author }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-700 dark:text-gray-300">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-gray-200 dark:border-gray-700">
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <td colspan="5" class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">Subtotal:</td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        </tr>
                        @if($transaction->shipping_cost > 0)
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <td colspan="5" class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">Shipping Cost:</td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-blue-600 dark:text-blue-400">Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="bg-gray-100 dark:bg-gray-800/50">
                            <td colspan="5" class="px-4 py-3 text-right text-base font-bold text-gray-900 dark:text-white">Grand Total:</td>
                            <td class="px-4 py-3 text-right text-lg font-bold text-green-600 dark:text-green-400">
                                Rp {{ number_format($transaction->total + $transaction->shipping_cost, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-common.component-card>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.transactions.edit', $transaction) }}" 
                class="rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-green-600">
                Edit Transaction
            </a>
            <a href="{{ route('admin.transactions.invoice', $transaction) }}" 
                target="_blank"
                class="rounded-lg bg-purple-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-purple-600">
                View Invoice
            </a>
            <a href="{{ route('admin.transactions.index') }}" 
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Back to List
            </a>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .leaflet-container {
        z-index: 1;
    }
</style>
@endpush

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map for delivery address
        const mapContainer = document.getElementById('deliveryMap');
        if (mapContainer) {
            const lat = parseFloat(mapContainer.dataset.lat);
            const lng = parseFloat(mapContainer.dataset.lng);
            const address = mapContainer.dataset.address;
            
          
            const map = L.map('deliveryMap').setView([lat, lng], 15);  
      
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            const marker = L.marker([lat, lng]).addTo(map);
        }
    });
</script>
@endpush
@endsection