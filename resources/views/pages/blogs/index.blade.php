@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Blog Posts" />

    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.blogs.create') }}" 
           class="rounded-lg bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
            + Add New Post
        </a>
    </div>

    <div class="rounded-lg bg-white shadow dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Image</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Title</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Category</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Author</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Views</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Created</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                    <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3">
                            @if($blog->featured_image)
                                <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="max-w-xs">
                                <p class="font-medium text-gray-800 dark:text-white/90 line-clamp-2">{{ $blog->title }}</p>
                                <p class="text-xs text-gray-400 mt-1 line-clamp-1">{{ Str::limit($blog->short_description, 60) }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ $blog->category }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $blog->author }}</td>
                        <td class="px-4 py-3">
                            @if($blog->is_published)
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    Published
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ number_format($blog->views) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $blog->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <!-- SHOW BUTTON - View Details -->
                                <a href="{{ route('admin.blogs.show', $blog) }}" 
                                   class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition-colors dark:text-green-400 dark:hover:bg-green-900/20"
                                   title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                
                                <!-- EDIT Button -->
                                <a href="{{ route('admin.blogs.edit', $blog) }}" 
                                   class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors dark:text-blue-400 dark:hover:bg-blue-900/20"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                
                                <!-- DELETE Button -->
                                <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="inline" onsubmit="return confirm('Delete this post?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors dark:text-red-400 dark:hover:bg-red-900/20" title="Delete">
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
                        <td colspan="8" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                                <p class="text-gray-500">No blog posts yet</p>
                                <a href="{{ route('admin.blogs.create') }}" class="mt-3 text-blue-500 hover:underline">Create your first post →</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
            {{ $blogs->links() }}
        </div>
    </div>
</div>
@endsection