@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Genre Details" />

    <div class="space-y-6">
        <!-- Genre Information -->
        <x-common.component-card title="Genre Information" desc="Details about the genre">
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Genre Name</label>
                        <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white/90">{{ $genre->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $genre->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</label>
                        <p class="mt-1 text-gray-800 dark:text-white/90">{{ $genre->updated_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Books</label>
                        <p class="mt-1">
                            <span class="inline-flex px-2 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                {{ $genre->books()->count() }} books
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </x-common.component-card>

        <!-- Books in this Genre -->
        @if($genre->books->count() > 0)
        <x-common.component-card title="Books in this Genre" desc="All books categorized under {{ $genre->name }}">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-gray-200 border-y dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Title</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Author</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Price</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Stock</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($genre->books as $book)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.books.show', $book) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                    {{ $book->title }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $book->author }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">${{ number_format($book->price, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $book->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $book->stock }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-common.component-card>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.genres.edit', $genre) }}" 
                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Edit Genre
            </a>
            <a href="{{ route('admin.genres.index') }}" 
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection