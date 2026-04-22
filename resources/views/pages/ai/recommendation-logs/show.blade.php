@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Recommendation Log Details" />

    <div class="space-y-6">
        <!-- Log Information -->
        <x-common.component-card title="Recommendation Details" desc="Information about the AI recommendation request">
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Log ID</label>
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
                                <p class="text-gray-800 dark:text-white/90">{{ $aiRecommendationLog->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $aiRecommendationLog->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Input Text</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <p class="text-gray-800 dark:text-white/90">{{ $aiRecommendationLog->input }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $aiRecommendationLog->create_at->format('F d, Y h:i:s A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Recommendations</label>
                        <p class="mt-1">
                            <span class="inline-flex px-2 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                {{ $aiRecommendationLog->books->count() }} books recommended
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </x-common.component-card>

        <!-- Recommended Books -->
        <x-common.component-card title="Recommended Books" desc="Books suggested by AI based on user input">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-gray-200 border-y dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">#</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Book Title</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Author</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Price</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Stock</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($aiRecommendationLog->books as $index => $book)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.books.show', $book) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                    {{ $book->title }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $book->author }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">${{ number_format($book->price, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $book->stock > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                    {{ $book->stock }} in stock
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-common.component-card>

        <!-- JSON Result (if available) -->
        @if($aiRecommendationLog->result)
        <x-common.component-card title="Raw AI Response" desc="Complete JSON response from AI model">
            <div class="overflow-x-auto">
                <pre class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto"><code>{{ json_encode($aiRecommendationLog->result, JSON_PRETTY_PRINT) }}</code></pre>
            </div>
        </x-common.component-card>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3">
            <form method="POST" action="{{ route('admin.ai.recommendation-logs.destroy', $aiRecommendationLog) }}" 
                  onsubmit="return confirm('Are you sure you want to delete this recommendation log?')"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600">
                    Delete Log
                </button>
            </form>
            <a href="{{ route('admin.ai.recommendation-logs.index') }}" 
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection