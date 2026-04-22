@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <x-common.page-breadcrumb pageTitle="AI Training Logs" />

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Trainings Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Trainings</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $totalTrainings }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Latest Loss Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Latest Loss</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                        {{ $latestLog ? number_format($latestLog->total_loss, 4) : 'N/A' }}
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Average Loss Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Average Loss</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                        {{ $averageLoss ? number_format($averageLoss, 4) : 'N/A' }}
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/30">
                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Average Time Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Average Time (s)</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                        {{ $averageTime ? number_format($averageTime, 2) : 'N/A' }}
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Training History</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View and manage AI model training logs</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <form method="GET" action="{{ route('admin.ai.training-logs.index') }}" class="relative">
                    <button type="submit" class="absolute -translate-y-1/2 left-4 top-1/2">
                        <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                        </svg>
                    </button>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search logs..." 
                           class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 xl:w-[250px]"/>
                </form>
                
                <!-- Retraining Button with Modal -->
                <button type="button" 
                        @click="$dispatch('open-modal', { modalId: 'retrain-modal' })"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-purple-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-purple-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Retrain Model
                </button>

                @if($totalTrainings > 0)
                <form method="POST" action="{{ route('admin.ai.training-logs.clear-all') }}" 
                      onsubmit="return confirm('Are you sure you want to clear all training logs? This action cannot be undone.')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Clear All
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Search Results Info -->
        @if(request('search'))
            <div class="px-5 mb-3 sm:px-6">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Showing results for: <span class="font-semibold text-gray-800 dark:text-white">"{{ request('search') }}"</span>
                    <a href="{{ route('admin.ai.training-logs.index') }}" class="ml-2 text-blue-500 hover:text-blue-600">Clear search</a>
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
                            <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">ID</th>
                            <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Total Loss</th>
                            <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Total Time (seconds)</th>
                            <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Started At</th>
                            <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-center text-theme-sm dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">#{{ $log->id }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full 
                                        @if($log->total_loss < 2) bg-green-500
                                        @elseif($log->total_loss < 5) bg-yellow-500
                                        @else bg-red-500
                                        @endif">
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ number_format($log->total_loss, 4) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ number_format($log->total_time, 2) }} s
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $log->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $log->created_at->format('h:i:s A') }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- View Details Button -->
                                    <button type="button" 
                                            @click="$dispatch('open-modal', { modalId: 'detail-modal-' + {{ $log->id }} })"
                                            class="p-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                            title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('admin.ai.training-logs.destroy', $log) }}" 
                                          onsubmit="return confirm('Are you sure you want to delete this training log?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                title="Delete Log">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Detail Modal for each log -->
                        <div x-data="{ modalId: null }" 
                             @open-modal.window="modalId = $event.detail.modalId"
                             @close-modal.window="modalId = null">
                            <template x-if="modalId === 'detail-modal-{{ $log->id }}'">
                                <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-black/50 p-4">
                                    <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-xl dark:bg-gray-900">
                                        <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-800">
                                            <div>
                                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">Training Log Details</h3>
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Log #{{ $log->id }}</p>
                                            </div>
                                            <button @click="$dispatch('close-modal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="p-6 space-y-4">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Log ID</label>
                                                    <p class="mt-1 text-gray-800 dark:text-white/90">#{{ $log->id }}</p>
                                                </div>
                                                <div>
                                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Loss</label>
                                                    <p class="mt-1 text-gray-800 dark:text-white/90">{{ number_format($log->total_loss, 6) }}</p>
                                                </div>
                                                <div>
                                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Time</label>
                                                    <p class="mt-1 text-gray-800 dark:text-white/90">{{ number_format($log->total_time, 2) }} seconds</p>
                                                </div>
                                                <div>
                                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Started At</label>
                                                    <p class="mt-1 text-gray-800 dark:text-white/90">{{ $log->created_at->format('F d, Y h:i:s A') }}</p>
                                                </div>
                                                <div class="col-span-2">
                                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed At</label>
                                                    <p class="mt-1 text-gray-800 dark:text-white/90">{{ $log->updated_at->format('F d, Y h:i:s A') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-end gap-3 border-t border-gray-200 p-4 dark:border-gray-800">
                                            <button @click="$dispatch('close-modal')" 
                                                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No training logs found</p>
                                    <p class="text-sm">Click "Retrain Model" to start your first training session</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Retraining Modal -->
<div x-data="{ modalId: null, epochs: 10, batchSize: 32 }" 
     @open-modal.window="modalId = $event.detail.modalId"
     @close-modal.window="modalId = null">
    <template x-if="modalId === 'retrain-modal'">
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-black/50 p-4">
            <div class="relative w-full max-w-md rounded-2xl bg-white shadow-xl dark:bg-gray-900">
                <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-800">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">Retrain AI Model</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure training parameters</p>
                    </div>
                    <button @click="$dispatch('close-modal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.ai.training-logs.retrain') }}">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Number of Epochs
                            </label>
                            <input type="number" 
                                   name="epochs" 
                                   x-model="epochs"
                                   min="1" 
                                   max="100"
                                   class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <p class="text-theme-xs text-gray-500 mt-1.5">Number of training iterations (default: 10)</p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Batch Size
                            </label>
                            <input type="number" 
                                   name="batch_size" 
                                   x-model="batchSize"
                                   min="1" 
                                   max="256"
                                   class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <p class="text-theme-xs text-gray-500 mt-1.5">Number of samples per gradient update (default: 32)</p>
                        </div>
                        <div class="rounded-lg bg-yellow-50 p-3 dark:bg-yellow-900/20">
                            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                <strong>Note:</strong> Retraining may take several minutes depending on your dataset size and configuration.
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-gray-200 p-4 dark:border-gray-800">
                        <button type="button" @click="$dispatch('close-modal')" 
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="rounded-lg bg-purple-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-purple-600">
                            Start Retraining
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection