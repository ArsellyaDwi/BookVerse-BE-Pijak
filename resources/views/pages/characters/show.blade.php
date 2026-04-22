@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Character Details" />

    <div class="space-y-6">
        <!-- Character Information -->
        <x-common.component-card title="Character Information" desc="Details about the character">
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Character Name</label>
                        <div class="flex items-center gap-3 mt-2">
                            <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <p class="text-xl font-semibold text-gray-800 dark:text-white/90">{{ $character->name }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                        <p class="mt-2 text-gray-800 dark:text-white/90">{{ $character->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</label>
                        <p class="mt-2 text-gray-800 dark:text-white/90">{{ $character->updated_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Books</label>
                        <p class="mt-2">
                            <span class="inline-flex px-2 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ $character->books()->count() }} books
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </x-common.component-card>

        <!-- Books with this Character -->
        @if($character->books->count() > 0)
        <x-common.component-card title="Books featuring this Character" desc="All books that include {{ $character->name }}">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-gray-200 border-y dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Title</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Author</th>
                            <th class