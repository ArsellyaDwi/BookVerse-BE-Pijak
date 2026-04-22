@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Store Settings" />

    <x-common.component-card title="Store Configuration" desc="Manage your store information">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <!-- Store Name -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Store Name <span class="text-error-500">*</span>
                    </label>
                    <input type="text" 
                           name="store_name" 
                           value="{{ old('store_name', $settings->store_name ?? '') }}"
                           class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('store_name') border-error-300 @enderror"
                           placeholder="Enter store name"
                           autofocus>
                    
                    @error('store_name')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Store Contact -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Store Contact
                    </label>
                    <input type="text" 
                           name="store_contact" 
                           value="{{ old('store_contact', $settings->store_contact ?? '') }}"
                           class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                           placeholder="Phone number or email">
                    
                    @error('store_contact')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                    <p class="text-theme-xs text-gray-500 mt-1.5">Customer support contact information</p>
                </div>

                <!-- Store Address -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Store Address
                    </label>
                    <textarea name="store_address" 
                              rows="4"
                              class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                              placeholder="Enter store address">{{ old('store_address', $settings->store_address ?? '') }}</textarea>
                    
                    @error('store_address')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                    <p class="text-theme-xs text-gray-500 mt-1.5">Physical store address or warehouse location</p>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <button type="submit" 
                        class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Save Settings
                    </button>
                </div>
            </div>
        </form>
    </x-common.component-card>
</div>
@endsection