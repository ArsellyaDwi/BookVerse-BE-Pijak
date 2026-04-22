@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Book List" />
    
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
                <!-- Header -->
                <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Books Catalog</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your book collection</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <form method="GET" action="{{ route('admin.books.index') }}" class="relative">
                            <button type="submit" class="absolute -translate-y-1/2 left-4 top-1/2">
                                <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                                </svg>
                            </button>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by title or author..." 
                                   class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-blue-800 xl:w-[300px]"/>
                        </form>
                        <!-- Import CSV Button -->
                        <button type="button" 
                        @click="$dispatch('open-modal', { modalId: 'import-modal' })"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-600">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 9H15M9 3V15M3 9L7 5M3 9L7 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M15 9L11 5M15 9L11 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            Import CSV
                        </button>

                        <!-- Modal Container -->
                        <div x-data="{ modalId: null }" 
                            @open-modal.window="modalId = $event.detail.modalId"
                            @close-modal.window="modalId = null">
                            <template x-if="modalId === 'import-modal'">
                                @include('pages.books.import')
                            </template>
                        </div>
                        <a href="{{ route('admin.books.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-600">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 3V15M3 9H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            Add New Book
                        </a>
                    </div>
                </div>

                <!-- Search Results Info -->
                @if(request('search'))
                    <div class="px-5 mb-3 sm:px-6">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Showing results for: <span class="font-semibold text-gray-800 dark:text-white">"{{ request('search') }}"</span>
                            <a href="{{ route('admin.books.index') }}" class="ml-2 text-blue-500 hover:text-blue-600">Clear search</a>
                        </div>
                    </div>
                @endif

                <!-- Table -->
                <div class="overflow-hidden">
                    <div class="max-w-full px-5 overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-gray-200 border-y dark:border-gray-700">
                                    <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Cover</th>
                                    <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Title</th>
                                    <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Author</th>
                                    <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Genre</th>
                                    <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Rating</th>
                                    <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Stock</th>
                                    <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Price</th>
                                    <th scope="col" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Published</th>
                                    <th scope="col" class="relative px-4 py-3 capitalize">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($books as $book)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="shrink-0 w-12 h-16">
                                                @if($book->cover_img)
                                                    <img class="w-12 h-16 object-cover rounded" src="{{ asset('storage/' . $book->cover_img) }}" alt="{{ $book->title }}">
                                                @else
                                                    <div class="w-12 h-16 bg-gray-100 dark:bg-gray-800 rounded flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $book->title }}
                                        </div>
                                        @if($book->series)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Series: {{ $book->series }}
                                            </div>
                                        @endif
                                        <div class="text-xs text-gray-400 dark:text-gray-500">
                                            ISBN: {{ $book->isbn ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700 dark:text-gray-300">
                                            {{ $book->author }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($book->genres as $genre)
                                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                    {{ $genre->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400">No genres</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ number_format($book->rating, 1) }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                ({{ $book->ratings ?? 0 }})
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $book->stock > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                            {{ $book->stock }} in stock
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            Rp {{ number_format($book->price, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $book->publish_date ? $book->publish_date->format('M d, Y') : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium text-right whitespace-nowrap">
                                        <div class="flex justify-center relative">
                                            <div x-data="{ dropdownOpen: false }" class="relative">
                                                <button @click="dropdownOpen = !dropdownOpen" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.99902 10.245C6.96552 10.245 7.74902 11.0285 7.74902 11.995V12.005C7.74902 12.9715 6.96552 13.755 5.99902 13.755C5.03253 13.755 4.24902 12.9715 4.24902 12.005V11.995C4.24902 11.0285 5.03253 10.245 5.99902 10.245ZM17.999 10.245C18.9655 10.245 19.749 11.0285 19.749 11.995V12.005C19.749 12.9715 18.9655 13.755 17.999 13.755C17.0325 13.755 16.249 12.9715 16.249 12.005V11.995C16.249 11.0285 17.0325 10.245 17.999 10.245ZM13.749 11.995C13.749 11.0285 12.9655 10.245 11.999 10.245C11.0325 10.245 10.249 11.0285 10.249 11.995V12.005C10.249 12.9715 11.0325 13.755 11.999 13.755C12.9655 13.755 13.749 12.9715 13.749 12.005V11.995Z" fill="currentColor" />
                                                    </svg>
                                                </button>
                                                <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800 dark:ring-gray-700" style="display: none;">
                                                    <div class="py-1">
                                                        <a href="{{ route('admin.books.show', $book->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">View Details</a>
                                                        <a href="{{ route('admin.books.edit', $book->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Edit</a>
                                                        <form method="POST" action="{{ route('admin.books.destroy', $book->id) }}" class="block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:text-red-400 dark:hover:bg-gray-700" onclick="return confirm('Are you sure you want to delete this book?')">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            <p class="text-lg font-medium">No books found</p>
                                            <p class="text-sm">Try adjusting your search or add a new book</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($books->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
                    <div class="flex items-center justify-between">
                        @if($books->onFirstPage())
                            <span class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-400 shadow-theme-xs opacity-50 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z" fill="currentColor"/>
                                </svg>
                                <span class="hidden sm:inline">Previous</span>
                            </span>
                        @else
                            <a href="{{ $books->previousPageUrl() }}" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z" fill="currentColor"/>
                                </svg>
                                <span class="hidden sm:inline">Previous</span>
                            </a>
                        @endif

                        <span class="block text-sm font-medium text-gray-700 dark:text-gray-400 sm:hidden">
                            Page {{ $books->currentPage() }} of {{ $books->lastPage() }}
                        </span>

                        <ul class="hidden items-center gap-0.5 sm:flex">
                            @foreach ($books->getUrlRange(1, $books->lastPage()) as $page => $url)
                                @if ($page == $books->currentPage())
                                    <li>
                                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-500 text-theme-sm font-medium text-white">
                                            {{ $page }}
                                        </span>
                                    </li>
                                @elseif ($page == 1 || $page == $books->lastPage() || ($page >= $books->currentPage() - 1 && $page <= $books->currentPage() + 1))
                                    <li>
                                        <a href="{{ $url }}" class="flex h-10 w-10 items-center justify-center rounded-lg text-theme-sm font-medium text-gray-700 hover:bg-blue-500/[0.08] hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-500">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @elseif (($page == $books->currentPage() - 2 && $books->currentPage() > 3) || ($page == $books->currentPage() + 2 && $books->currentPage() < $books->lastPage() - 2))
                                    <li>
                                        <span class="flex h-10 w-10 items-center justify-center text-gray-500">...</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>

                        @if($books->hasMorePages())
                            <a href="{{ $books->nextPageUrl() }}" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                <span class="hidden sm:inline">Next</span>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill="currentColor"/>
                                </svg>
                            </a>
                        @else
                            <span class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-400 shadow-theme-xs opacity-50 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800">
                                <span class="hidden sm:inline">Next</span>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill="currentColor"/>
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection