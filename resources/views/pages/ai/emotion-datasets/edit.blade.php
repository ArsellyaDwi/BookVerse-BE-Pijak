@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Edit Emotion Dataset" />

    <x-common.component-card title="Edit Dataset Information" desc="Update the emotion label and text content">
        <form action="{{ route('admin.ai.emotion-datasets.update', $aiEmotionDataset) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <!-- Emotion Label -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Emotion Label <span class="text-error-500">*</span>
                    </label>
                    <input type="text" name="labels" value="{{old('labels')}}" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('text') border-error-300 @enderror" />
                    
                    @error('labels')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Text Content -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Text Content <span class="text-error-500">*</span>
                    </label>
                    <textarea name="text" 
                              rows="6"
                              class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('text') border-error-300 @enderror"
                              placeholder="Enter the text content for emotion analysis...">{{ old('text', $aiEmotionDataset->text) }}</textarea>
                    
                    @error('text')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.ai.emotion-datasets.index') }}" 
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Update Dataset
                    </button>
                </div>
            </div>
        </form>
    </x-common.component-card>
</div>
@endsection