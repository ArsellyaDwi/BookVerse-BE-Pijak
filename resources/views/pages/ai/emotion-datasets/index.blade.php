@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <x-common.page-breadcrumb pageTitle="AI Emotion Datasets" />

    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Emotion Recognition Datasets</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage training data for emotion detection</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <form method="GET" action="{{ route('admin.ai.emotion-datasets.index') }}" class="relative">
                    <button type="submit" class="absolute -translate-y-1/2 left-4 top-1/2">
                        <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                        </svg>
                    </button>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by label or text..." 
                           class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 xl:w-[300px]"/>
                </form>
                <button type="button" 
                        @click="$dispatch('open-modal', { modalId: 'import-modal' })"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-600">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 9H15M9 3V15M3 9L7 5M3 9L7 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M15 9L11 5M15 9L11 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Import CSV
                </button>
                <a href="{{ route('admin.ai.emotion-datasets.create') }}" 
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-600">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 3V15M3 9H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Add New Dataset
                </a>
            </div>
        </div>

        <!-- Search Results Info -->
        @if(request('search'))
            <div class="px-5 mb-3 sm:px-6">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Showing results for: <span class="font-semibold text-gray-800 dark:text-white">"{{ request('search') }}"</span>
                    <a href="{{ route('admin.ai.emotion-datasets.index') }}" class="ml-2 text-blue-500 hover:text-blue-600">Clear search</a>
                </div>
            </div>
        @endif

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mx-5 mb-4 sm:mx-6">
                <div class="rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-300">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-5 mb-4 sm:mx-6">
                <div class="rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-red-900/30 dark:text-red-300">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-hidden">
            <div class="max-w-full px-5 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-gray-200 border-y dark:border-gray-700">
                            <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400 w-16">ID</th>
                            <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Label</th>
                            <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Text</th>
                            <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Created At</th>
                            <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-center text-theme-sm dark:text-gray-400 w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($datasets as $dataset)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">#{{ $dataset->id }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                    @if($dataset->labels == 'happy') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                    @elseif($dataset->labels == 'sad') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                    @elseif($dataset->labels == 'angry') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                    @elseif($dataset->labels == 'fear') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                    @elseif($dataset->labels == 'surprise') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                    @elseif($dataset->labels == 'love') bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-300
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst($dataset->labels) }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-700 dark:text-gray-300 max-w-md truncate">
                                    {{ $dataset->text }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $dataset->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $dataset->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.ai.emotion-datasets.show', $dataset) }}" 
                                       class="p-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                       title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.ai.emotion-datasets.edit', $dataset) }}" 
                                       class="p-2 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-colors"
                                       title="Edit Dataset">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('admin.ai.emotion-datasets.destroy', $dataset) }}" 
                                          onsubmit="return confirm('Are you sure you want to delete this dataset? This action cannot be undone.')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                title="Delete Dataset">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No emotion datasets found</p>
                                    <p class="text-sm">Click "Add New Dataset" or "Import CSV" to add training data</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($datasets->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
            {{ $datasets->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Container -->
<div x-data="{ modalId: null }" 
     @open-modal.window="modalId = $event.detail.modalId"
     @close-modal.window="modalId = null">
    <template x-if="modalId === 'import-modal'">
        @include('pages.ai.emotion-datasets.import')
    </template>
</div>
@endsection