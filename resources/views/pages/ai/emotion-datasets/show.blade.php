@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Emotion Dataset Details" />

    <div class="space-y-6">
        <x-common.component-card title="Dataset Information" desc="Details of the emotion training data">
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">#{{ $aiEmotionDataset->id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Emotion Label</label>
                        <p class="mt-1">
                            <span class="inline-flex px-2 py-1 text-sm font-medium rounded-full 
                                @if($aiEmotionDataset->labels == 'happy') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                @elseif($aiEmotionDataset->labels == 'sad') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                @elseif($aiEmotionDataset->labels == 'angry') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                @elseif($aiEmotionDataset->labels == 'fear') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                @elseif($aiEmotionDataset->labels == 'surprise') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                @elseif($aiEmotionDataset->labels == 'love') bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300
                                @endif">
                                {{ ucfirst($aiEmotionDataset->labels) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $aiEmotionDataset->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $aiEmotionDataset->updated_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Text Content</label>
                    <div class="mt-2 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <p class="text-gray-800 dark:text-white/90 leading-relaxed">{{ $aiEmotionDataset->text }}</p>
                    </div>
                </div>
            </div>
        </x-common.component-card>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.ai.emotion-datasets.edit', $aiEmotionDataset) }}" 
                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Edit Dataset
            </a>
            <a href="{{ route('admin.ai.emotion-datasets.index') }}" 
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection