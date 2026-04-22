@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Edit Payment Method" />

    <x-common.component-card title="Edit Payment Method Information" desc="Update the payment method details">
        <form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <!-- Payment Method Name -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Payment Method Name <span class="text-error-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $paymentMethod->name) }}"
                           class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('name') border-error-300 @enderror"
                           placeholder="e.g., Credit Card, PayPal, Bank Transfer, etc.">
                    
                    @error('name')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Description
                    </label>
                    <textarea name="description" 
                              rows="3"
                              class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('description') border-error-300 @enderror"
                              placeholder="Enter a description for this payment method (optional)">{{ old('description', $paymentMethod->description) }}</textarea>
                    
                    @error('description')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image -->
                @if($paymentMethod->image)
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Current Image
                    </label>
                    <div class="mt-2">
                        <img src="{{ Storage::url($paymentMethod->image) }}" 
                             alt="{{ $paymentMethod->name }}" 
                             class="h-20 w-auto object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                    </div>
                </div>
                @endif

                <!-- Image Upload -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        @if($paymentMethod->image)
                            Replace Image (optional)
                        @else
                            Payment Method Image
                        @endif
                    </label>
                    
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
                            
                            <input x-ref="fileInput" type="file" name="image" @change="handleFiles(Array.from($event.target.files))" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="hidden" />

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
                                    <span x-show="!isDragging">Drag & Drop New Image Here</span>
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
                    
                    @error('image')
                        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.payment-methods.index') }}" 
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Update Payment Method
                    </button>
                </div>
            </div>
        </form>
    </x-common.component-card>
</div>
@endsection