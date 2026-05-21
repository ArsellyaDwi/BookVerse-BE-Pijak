@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Blog Post Details" />

    <div class="space-y-6">
        <!-- Post Header -->
        <x-common.component-card title="{{ $blog->title }}" desc="Blog Post Information">
            <div class="flex flex-col gap-6">
                @if($blog->featured_image)
                    <div class="w-full md:w-1/2 lg:w-1/3">
                        <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full rounded-lg shadow-lg">
                    </div>
                @endif
                
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</label>
                        <p class="text-gray-800 dark:text-white/90">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $blog->category }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Author</label>
                        <p class="text-gray-800 dark:text-white/90">{{ $blog->author }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        <p class="text-gray-800 dark:text-white/90">
                            @if($blog->is_published)
                                <span class="text-green-600">Published</span>
                            @else
                                <span class="text-yellow-600">Draft</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Read Time</label>
                        <p class="text-gray-800 dark:text-white/90">{{ $blog->read_time ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Published Date</label>
                        <p class="text-gray-800 dark:text-white/90">{{ $blog->published_at ? $blog->published_at->format('F d, Y') : 'Not published yet' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Views</label>
                        <p class="text-gray-800 dark:text-white/90">{{ number_format($blog->views) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Likes</label>
                        <p class="text-gray-800 dark:text-white/90">{{ number_format($blog->likes) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                        <p class="text-gray-800 dark:text-white/90">{{ $blog->created_at->format('F d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</label>
                        <p class="text-gray-800 dark:text-white/90">{{ $blog->updated_at->format('F d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </x-common.component-card>

        <!-- Short Description -->
        <x-common.component-card title="Short Description" desc="Brief summary of the article">
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $blog->short_description }}</p>
        </x-common.component-card>

        <!-- Full Content -->
        <x-common.component-card title="Content" desc="Full article content">
            <div class="prose max-w-none dark:prose-invert">
                {!! nl2br(e($blog->content)) !!}
            </div>
        </x-common.component-card>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.blogs.edit', $blog) }}" 
                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Edit Post
            </a>
            <a href="{{ route('admin.blogs.index') }}" 
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection