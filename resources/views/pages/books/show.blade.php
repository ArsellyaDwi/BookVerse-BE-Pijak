@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Book Details" />

    <div class="space-y-6">
        <!-- Book Cover and Basic Info -->
        <x-common.component-card title="{{ $book->title }}" desc="Book Information">
            <div class="flex flex-col gap-6 sm:flex-row">
                @if($book->cover_img)
                    <div class="sm:w-1/3">
                        <img src="{{ Storage::url($book->cover_img) }}" alt="{{ $book->title }}" class="w-full rounded-lg shadow-lg">
                    </div>
                @endif
                
                <div class="flex-1 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Author</label>
                            <p class="text-gray-800 dark:text-white/90">{{ $book->author }}</p>
                        </div>
                        @if($book->series)
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Series</label>
                            <p class="text-gray-800 dark:text-white/90">{{ $book->series }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">ISBN</label>
                            <p class="text-gray-800 dark:text-white/90">{{ $book->isbn ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Language</label>
                            <p class="text-gray-800 dark:text-white/90">{{ $book->language ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Publisher</label>
                            <p class="text-gray-800 dark:text-white/90">{{ $book->publisher ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Publish Date</label>
                            <p class="text-gray-800 dark:text-white/90">{{ $book->publish_date?->format('F d, Y') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Pages</label>
                            <p class="text-gray-800 dark:text-white/90">{{ $book->pages ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock</label>
                            <p class="text-gray-800 dark:text-white/90">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $book->stock > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                    {{ $book->stock }} in stock
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Price</label>
                            <p class="text-2xl font-bold text-brand-600 dark:text-brand-400">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Rating</label>
                            <div class="flex items-center gap-1">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($book->rating))
                                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @elseif($i - 0.5 <= $book->rating)
                                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">({{ number_format($book->rating, 1) }} based on {{ $book->ratings }} reviews)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-common.component-card>

        <!-- Description -->
        @if($book->description)
        <x-common.component-card title="Description" desc="Book description">
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $book->description }}</p>
        </x-common.component-card>
        @endif

        <!-- Genres -->
        @if($book->genres->count() > 0)
        <x-common.component-card title="Genres" desc="Book categories">
            <div class="flex flex-wrap gap-2">
                @foreach($book->genres as $genre)
                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                        {{ $genre->name }}
                    </span>
                @endforeach
            </div>
        </x-common.component-card>
        @endif

        <!-- Characters -->
        @if($book->characters->count() > 0)
        <x-common.component-card title="Characters" desc="Characters in this book">
            <div class="flex flex-wrap gap-2">
                @foreach($book->characters as $character)
                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                        {{ $character->name }}
                    </span>
                @endforeach
            </div>
        </x-common.component-card>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.books.edit', $book) }}" 
                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Edit Book
            </a>
            <a href="{{ route('admin.books.index') }}" 
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection