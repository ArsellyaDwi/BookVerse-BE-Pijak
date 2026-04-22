@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Add New Character" />

    <x-common.component-card title="Character Information" desc="Enter the details for the new character">
        <form action="{{ route('admin.characters.store') }}" method="POST">
            @csrf

            <div class="space-y-5">
                <!-- Character Name -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Character Name <span class="text-error-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('name') border-error-300 @enderror"
                           placeholder="e.g., Harry Potter, Sherlock Holmes, etc."
                           autofocus>
                    
                    @error('name')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                    
                    <p class="text-theme-xs text-gray-500 mt-1.5">Enter a unique character name. This will be used to associate with books.</p>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.characters.index') }}" 
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Create Character
                    </button>
                </div>
            </div>
        </form>
    </x-common.component-card>
</div>
@endsection