@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Delivery Method Details" />

    <div class="space-y-6">
        <x-common.component-card title="Delivery Method Information" desc="Shipping method details">
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Method Name</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $deliveryMethod->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        <p class="mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $deliveryMethod->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400' }}">
                                {{ $deliveryMethod->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Base Price</label>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($deliveryMethod->base_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Books per Multiplier</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $deliveryMethod->books_per_multiplier }} books</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Estimated Delivery</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $deliveryMethod->estimated_days }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $deliveryMethod->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
                
                @if($deliveryMethod->description)
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                    <p class="mt-1 text-gray-700 dark:text-gray-300">{{ $deliveryMethod->description }}</p>
                </div>
                @endif
            </div>
        </x-common.component-card>

        <!-- Shipping Calculation Examples -->
        <x-common.component-card title="Shipping Calculation" desc="How shipping costs are calculated">
            <div class="space-y-4">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Formula:</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-mono">
                        Shipping Cost = Base Price (Rp {{ number_format($deliveryMethod->base_price, 0, ',', '.') }}) × Multiplier
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-mono mt-1">
                        Multiplier = ceil(Total Books / {{ $deliveryMethod->books_per_multiplier }})
                    </p>
                </div>
                
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Example Costs by Quantity:</h4>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                        @for($i = 1; $i <= 12; $i++)
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $i }} {{ $i == 1 ? 'book' : 'books' }}</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">
                                Rp {{ number_format($deliveryMethod->calculateShippingCost($i), 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                Multiplier: {{ ceil($i / $deliveryMethod->books_per_multiplier) }}x
                            </p>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </x-common.component-card>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.delivery-methods.edit', $deliveryMethod) }}" 
                class="rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-green-600">
                Edit Delivery Method
            </a>
            <a href="{{ route('admin.delivery-methods.index') }}" 
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection