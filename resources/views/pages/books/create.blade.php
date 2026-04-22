@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Add New Book" />

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="space-y-6">
            <!-- Basic Information -->
            <x-common.component-card title="Basic Information" desc="Enter the basic details of the book">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <!-- Title -->
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Title <span class="text-error-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('title') border-error-300 @enderror"
                            placeholder="Enter book title">
                        @error('title')
                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Series -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Series
                        </label>
                        <input type="text" name="series" value="{{ old('series') }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            placeholder="Book series (optional)">
                    </div>

                    <!-- Author -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Author <span class="text-error-500">*</span>
                        </label>
                        <input type="text" name="author" value="{{ old('author') }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('author') border-error-300 @enderror"
                            placeholder="Author name">
                        @error('author')
                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Description
                    </label>
                    <textarea name="description" rows="4"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        placeholder="Book description">{{ old('description') }}</textarea>
                </div>
            </x-common.component-card>

            <!-- Book Details -->
            <x-common.component-card title="Book Details" desc="Additional information about the book">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <!-- ISBN -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            ISBN
                        </label>
                        <input type="text" name="isbn" value="{{ old('isbn') }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            placeholder="ISBN number">
                        @error('isbn')
                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Language -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Language
                        </label>
                        <input type="text" name="language" value="{{ old('language') }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            placeholder="English, Spanish, etc.">
                    </div>

                    <!-- Publisher -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Publisher
                        </label>
                        <input type="text" name="publisher" value="{{ old('publisher') }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            placeholder="Publisher name">
                    </div>

                    <!-- Publish Date -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Publish Date
                        </label>
                        <x-form.date-picker 
                            name="publish_date"
                            placeholder="Select publish date" 
                            defaultDate="{{ old('publish_date') }}" 
                        />
                    </div>

                    <!-- Pages -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Pages
                        </label>
                        <input type="number" name="pages" value="{{ old('pages') }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            placeholder="Number of pages">
                    </div>

                    <!-- Stock -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Stock <span class="text-error-500">*</span>
                        </label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('stock') border-error-300 @enderror"
                            placeholder="Quantity in stock">
                        @error('stock')
                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Price <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                                class="pl-4 dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent  pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('price') border-error-300 @enderror"
                                placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-common.component-card>

            <!-- Genres and Characters -->
            <x-common.component-card title="Categories" desc="Select genres and characters for this book">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <!-- Genres -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Genres
                        </label>
                        <div x-data="{
                            open: false,
                            selected: [],
                            options: {{ json_encode($genres->map(fn($g) => ['id' => $g->id, 'name' => $g->name])) }},
                            toggleOption(id) {
                                if (this.selected.includes(id)) {
                                    this.selected = this.selected.filter(i => i !== id);
                                } else {
                                    this.selected.push(id);
                                }
                            },
                            isSelected(id) {
                                return this.selected.includes(id);
                            }
                        }" class="relative" @click.away="open = false">
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="genres[]" :value="id">
                            </template>

                            <div @click="open = !open"
                                class="shadow-theme-xs flex min-h-11 cursor-pointer gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 transition dark:border-gray-700 dark:bg-gray-900">
                                <div class="flex flex-1 flex-wrap items-center gap-2">
                                    <template x-for="id in selected" :key="id">
                                        <div class="group flex items-center justify-center rounded-full border-[0.7px] border-transparent bg-gray-100 py-1 pr-2 pl-2.5 text-sm text-gray-800 hover:border-gray-200 dark:bg-gray-800 dark:text-white/90">
                                            <span x-text="options.find(o => o.id === id)?.name"></span>
                                            <button type="button" @click.stop="toggleOption(id)"
                                                class="ml-1 text-gray-500 hover:text-gray-700 dark:text-gray-400">
                                                <svg class="fill-current" width="14" height="14" viewBox="0 0 14 14">
                                                    <path fill-rule="evenodd" d="M3.40717 4.46881C3.11428 4.17591 3.11428 3.70104 3.40717 3.40815C3.70006 3.11525 4.17494 3.11525 4.46783 3.40815L6.99943 5.93975L9.53095 3.40822C9.82385 3.11533 10.2987 3.11533 10.5916 3.40822C10.8845 3.70112 10.8845 4.17599 10.5916 4.46888L8.06009 7.00041L10.5916 9.53193C10.8845 9.82482 10.8845 10.2997 10.5916 10.5926C10.2987 10.8855 9.82385 10.8855 9.53095 10.5926L6.99943 8.06107L4.46783 10.5927C4.17494 10.8856 3.70006 10.8856 3.40717 10.5927C3.11428 10.2998 3.11428 9.8249 3.40717 9.53201L5.93877 7.00041L3.40717 4.46881Z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                    <span x-show="selected.length === 0" class="text-sm text-gray-500 dark:text-gray-400">
                                        Select genres...
                                    </span>
                                </div>
                                <div class="flex items-start pt-1.5">
                                    <svg class="h-5 w-5 shrink-0 text-gray-500 transition-transform dark:text-gray-400"
                                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            <div x-show="open" x-cloak
                                class="absolute z-50 mt-1 w-full overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900"
                                style="max-height: 16rem">
                                <div class="overflow-y-auto" style="max-height: 16rem">
                                    <template x-for="option in options" :key="option.id">
                                        <div @click="toggleOption(option.id)"
                                            class="cursor-pointer border-b border-gray-200 px-4 py-3 text-sm transition last:border-b-0 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <span class="text-gray-800 dark:text-white/90" x-text="option.name"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Characters -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Characters
                        </label>
                        <div x-data="{
                            open: false,
                            selected: [],
                            options: {{ json_encode($characters->map(fn($c) => ['id' => $c->id, 'name' => $c->name])) }},
                            toggleOption(id) {
                                if (this.selected.includes(id)) {
                                    this.selected = this.selected.filter(i => i !== id);
                                } else {
                                    this.selected.push(id);
                                }
                            },
                            isSelected(id) {
                                return this.selected.includes(id);
                            }
                        }" class="relative" @click.away="open = false">
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="characters[]" :value="id">
                            </template>

                            <div @click="open = !open"
                                class="shadow-theme-xs flex min-h-11 cursor-pointer gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 transition dark:border-gray-700 dark:bg-gray-900">
                                <div class="flex flex-1 flex-wrap items-center gap-2">
                                    <template x-for="id in selected" :key="id">
                                        <div class="group flex items-center justify-center rounded-full border-[0.7px] border-transparent bg-gray-100 py-1 pr-2 pl-2.5 text-sm text-gray-800 hover:border-gray-200 dark:bg-gray-800 dark:text-white/90">
                                            <span x-text="options.find(o => o.id === id)?.name"></span>
                                            <button type="button" @click.stop="toggleOption(id)"
                                                class="ml-1 text-gray-500 hover:text-gray-700 dark:text-gray-400">
                                                <svg class="fill-current" width="14" height="14" viewBox="0 0 14 14">
                                                    <path fill-rule="evenodd" d="M3.40717 4.46881C3.11428 4.17591 3.11428 3.70104 3.40717 3.40815C3.70006 3.11525 4.17494 3.11525 4.46783 3.40815L6.99943 5.93975L9.53095 3.40822C9.82385 3.11533 10.2987 3.11533 10.5916 3.40822C10.8845 3.70112 10.8845 4.17599 10.5916 4.46888L8.06009 7.00041L10.5916 9.53193C10.8845 9.82482 10.8845 10.2997 10.5916 10.5926C10.2987 10.8855 9.82385 10.8855 9.53095 10.5926L6.99943 8.06107L4.46783 10.5927C4.17494 10.8856 3.70006 10.8856 3.40717 10.5927C3.11428 10.2998 3.11428 9.8249 3.40717 9.53201L5.93877 7.00041L3.40717 4.46881Z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                    <span x-show="selected.length === 0" class="text-sm text-gray-500 dark:text-gray-400">
                                        Select characters...
                                    </span>
                                </div>
                                <div class="flex items-start pt-1.5">
                                    <svg class="h-5 w-5 shrink-0 text-gray-500 transition-transform dark:text-gray-400"
                                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            <div x-show="open" x-cloak
                                class="absolute z-50 mt-1 w-full overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900"
                                style="max-height: 16rem">
                                <div class="overflow-y-auto" style="max-height: 16rem">
                                    <template x-for="option in options" :key="option.id">
                                        <div @click="toggleOption(option.id)"
                                            class="cursor-pointer border-b border-gray-200 px-4 py-3 text-sm transition last:border-b-0 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <span class="text-gray-800 dark:text-white/90" x-text="option.name"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-common.component-card>

            <!-- Cover Image -->
            <x-common.component-card title="Book Cover" desc="Upload the book cover image">
                <div x-data="{
                    isDragging: false,
                    files: [],
                    handleDrop(e) {
                        this.isDragging = false;
                        const droppedFiles = Array.from(e.dataTransfer.files);
                        this.handleFiles(droppedFiles);
                    },
                    handleFiles(selectedFiles) {
                        const validTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'];
                        const validFiles = selectedFiles.filter(file => validTypes.includes(file.type));
                        
                        if (validFiles.length > 0) {
                            this.files = validFiles;
                        }
                    },
                    removeFile(index) {
                        this.files.splice(index, 1);
                    }
                }" class="transition border border-gray-300 border-dashed cursor-pointer dark:hover:border-brand-500 dark:border-gray-700 rounded-xl hover:border-brand-500">
                    <div 
                        @drop.prevent="handleDrop($event)"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @click="$refs.fileInput.click()"
                        :class="isDragging ? 'border-brand-500 bg-gray-100 dark:bg-gray-800' : 'border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-900'"
                        class="dropzone rounded-xl border-dashed border-gray-300 p-7 lg:p-10 transition-colors cursor-pointer">
                        
                        <input x-ref="fileInput" type="file" name="cover_img" @change="handleFiles(Array.from($event.target.files))" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="hidden" />

                        <div class="flex flex-col items-center m-0">
                            <div class="mb-[22px] flex justify-center">
                                <div class="flex h-[68px] w-[68px] items-center justify-center rounded-full bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                    <svg class="fill-current" width="29" height="28" viewBox="0 0 29 28">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 10.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z" />
                                    </svg>
                                </div>
                            </div>

                            <h4 class="mb-3 font-semibold text-gray-800 text-theme-xl dark:text-white/90">
                                <span x-show="!isDragging">Drag & Drop Cover Image Here</span>
                                <span x-show="isDragging" x-cloak>Drop Image Here</span>
                            </h4>

                            <span class="text-center mb-5 block w-full max-w-[290px] text-sm text-gray-700 dark:text-gray-400">
                                Drag and drop your PNG, JPG, WebP, SVG image here or browse
                            </span>

                            <span class="font-medium underline text-theme-sm text-brand-500">
                                Browse File
                            </span>
                        </div>
                    </div>

                    <div x-show="files.length > 0" class="mt-4 p-4 border-t border-gray-200 dark:border-gray-700" x-cloak>
                        <template x-for="(file, index) in files" :key="index">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300" x-text="file.name"></span>
                                </div>
                                <button @click.stop="removeFile(index)" type="button" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
                @error('cover_img')
                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                @enderror
            </x-common.component-card>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.books.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                    Cancel
                </a>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Create Book
                </button>
            </div>
        </div>
    </form>
</div>
@endsection