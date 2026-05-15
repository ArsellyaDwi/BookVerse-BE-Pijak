@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Emotion Analysis Details" />

    <div class="space-y-6">
        <!-- Log Information -->
        <x-common.component-card title="Analysis Details" desc="Information about the AI emotion analysis request">
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Analysis ID</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">#{{ $aiRecommendationLog->id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">User</label>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white/90">{{ $aiRecommendationLog->user->name ?? 'Guest User' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $aiRecommendationLog->user->email ?? 'No email' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Input Text</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <p class="text-gray-800 dark:text-white/90">{{ $aiRecommendationLog->input ?? 'No input provided' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Analyzed At</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $aiRecommendationLog->create_at ? $aiRecommendationLog->create_at->format('F d, Y h:i:s A') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Detected Emotions</label>
                        <p class="mt-1">
                            <span class="inline-flex px-2 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                {{ is_array($aiRecommendationLog->result) ? count($aiRecommendationLog->result) : 0 }} emotions detected
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </x-common.component-card>

        <!-- Detected Emotions -->
        <x-common.component-card title="Detected Emotions" desc="Emotions identified by AI from the input text">
            @php
                $emotions = is_array($aiRecommendationLog->result) ? $aiRecommendationLog->result : [];
            @endphp
            
            @if(count($emotions) > 0)
                <div class="space-y-4">
                    <!-- Primary Emotion Highlight -->
                    <div class="p-4 rounded-lg bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Primary Emotion</h4>
                        <div class="flex items-center gap-3">
                            @php $topEmotion = $emotions[0]; @endphp
                            <div class="text-4xl">
                                @if($topEmotion['emotion'] == 'happiness') 😊
                                @elseif($topEmotion['emotion'] == 'sadness') 😢
                                @elseif($topEmotion['emotion'] == 'anger') 😠
                                @elseif($topEmotion['emotion'] == 'fear') 😨
                                @elseif($topEmotion['emotion'] == 'love') ❤️
                                @elseif($topEmotion['emotion'] == 'gratitude') 🙏
                                @elseif($topEmotion['emotion'] == 'relief') 😌
                                @else 🤖
                                @endif
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-800 dark:text-white/90">
                                    {{ ucfirst($topEmotion['emotion']) }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Confidence: {{ number_format($topEmotion['confidence'] * 100, 1) }}%
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $topEmotion['confidence'] * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- All Detected Emotions -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">All Detected Emotions</h4>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach($emotions as $index => $emotion)
                            <div class="p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg">
                                            @if($emotion['emotion'] == 'happiness') 😊
                                            @elseif($emotion['emotion'] == 'sadness') 😢
                                            @elseif($emotion['emotion'] == 'anger') 😠
                                            @elseif($emotion['emotion'] == 'fear') 😨
                                            @elseif($emotion['emotion'] == 'love') ❤️
                                            @elseif($emotion['emotion'] == 'gratitude') 🙏
                                            @elseif($emotion['emotion'] == 'relief') 😌
                                            @else 🤖
                                            @endif
                                        </span>
                                        <span class="font-medium text-gray-800 dark:text-white/90">
                                            {{ ucfirst($emotion['emotion']) }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                                        #{{ $index + 1 }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Confidence</span>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">
                                        {{ number_format($emotion['confidence'] * 100, 1) }}%
                                    </span>
                                </div>
                                <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                                    <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ $emotion['confidence'] * 100 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">No emotions were detected from the input text</p>
                </div>
            @endif
        </x-common.component-card>

        <!-- Emotion Analysis Details -->
        <x-common.component-card title="Analysis Summary" desc="Detailed breakdown of emotion detection">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ count($emotions) }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Emotions Detected</div>
                </div>
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        @if(!empty($emotions))
                            {{ number_format($emotions[0]['confidence'] * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Highest Confidence</div>
                </div>
                <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $aiRecommendationLog->create_at ? $aiRecommendationLog->create_at->diffForHumans() : 'N/A' }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Analysis Age</div>
                </div>
                <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                    <div class="text-xl font-bold text-orange-600 dark:text-orange-400 truncate">
                        {{ $aiRecommendationLog->user->name ?? 'Guest User' }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Requested By</div>
                </div>
            </div>
        </x-common.component-card>

        <!-- Raw JSON Response -->
        @if($aiRecommendationLog->result)
        <x-common.component-card title="Raw AI Response" desc="Complete JSON response from emotion detection model">
            <div class="overflow-x-auto">
                <pre class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto"><code>{{ json_encode($aiRecommendationLog->result, JSON_PRETTY_PRINT) }}</code></pre>
            </div>
        </x-common.component-card>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.ai.recommendation-logs.index') }}" 
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection