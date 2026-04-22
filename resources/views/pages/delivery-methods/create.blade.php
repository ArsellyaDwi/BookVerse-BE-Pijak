@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Add Delivery Method" />

    <x-common.component-card title="Delivery Method Information" desc="Enter the shipping method details">
        <form action="{{ route('admin.delivery-methods.store') }}" method="POST">
            @csrf

            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Method Name <span class="text-error-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('name') border-error-300 @enderror"
                           placeholder="e.g., Standard Delivery, Express Delivery"
                           autofocus>
                    
                    @error('name')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Description
                    </label>
                    <textarea name="description" 
                              rows="3"
                              class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('description') border-error-300 @enderror"
                              placeholder="Enter a description for this delivery method">{{ old('description') }}</textarea>
                    
                    @error('description')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Base Price -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Base Price (per multiplier) <span class="text-error-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="number" 
                               name="base_price" 
                               value="{{ old('base_price') }}"
                               step="1000"
                               class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-12 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('base_price') border-error-300 @enderror"
                               placeholder="0">
                    </div>
                    
                    @error('base_price')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                    <p class="text-theme-xs text-gray-500 mt-1.5">Base price for each multiplier (e.g., 1-3 books = 1x base price, 4-7 books = 2x base price)</p>
                </div>

                <!-- Books per Multiplier -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Books per Multiplier <span class="text-error-500">*</span>
                    </label>
                    <input type="number" 
                           name="books_per_multiplier" 
                           value="{{ old('books_per_multiplier', 4) }}"
                           min="1"
                           class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('books_per_multiplier') border-error-300 @enderror"
                           placeholder="4">
                    
                    @error('books_per_multiplier')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                    <p class="text-theme-xs text-gray-500 mt-1.5">Number of books that trigger a multiplier (e.g., 4 means 1-3 books = 1x, 4-7 books = 2x)</p>
                </div>

                <!-- Estimated Delivery Days -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Min Days
                        </label>
                        <input type="number" 
                               name="estimated_days_min" 
                               value="{{ old('estimated_days_min') }}"
                               min="1"
                               class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                               placeholder="3">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Max Days
                        </label>
                        <input type="number" 
                               name="estimated_days_max" 
                               value="{{ old('estimated_days_max') }}"
                               min="1"
                               class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                               placeholder="7">
                    </div>
                </div>

                <!-- Active Status -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Status
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
                    </label>
                    <p class="text-theme-xs text-gray-500 mt-1.5">Inactive methods won't be available for customers to select</p>
                </div>

                <!-- Shipping Calculation Preview -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Shipping Calculation Preview</h4>
                    <div class="space-y-2 text-sm">
                        <p class="text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Formula:</span> Base Price × Multiplier
                        </p>
                        <p class="text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Multiplier:</span> ceil(Total Books / Books per Multiplier)
                        </p>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Example calculations:</p>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>1 book: Base × 1</div>
                                <div>4 books: Base × 2</div>
                                <div>2 books: Base × 1</div>
                                <div>8 books: Base × 2</div>
                                <div>3 books: Base × 1</div>
                                <div>9 books: Base × 3</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.delivery-methods.index') }}" 
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Create Delivery Method
                    </button>
                </div>
            </div>
        </form>
    </x-common.component-card>
</div>
@endsection