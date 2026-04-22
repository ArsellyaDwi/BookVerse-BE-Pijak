<div x-data="{
    isDragging: false,
    file: null,
    fileName: '',
    uploading: false,
    handleDrop(e) {
        this.isDragging = false;
        const droppedFiles = Array.from(e.dataTransfer.files);
        this.handleFile(droppedFiles[0]);
    },
    handleFile(selectedFile) {
        if (selectedFile && selectedFile.type === 'text/csv') {
            this.file = selectedFile;
            this.fileName = selectedFile.name;
        } else {
            alert('Please upload a valid CSV file');
        }
    },
    removeFile() {
        this.file = null;
        this.fileName = '';
        this.$refs.fileInput.value = '';
    },
    async submitForm() {
        if (!this.file) {
            alert('Please select a CSV file to import');
            return;
        }
        
        this.uploading = true;
        const formData = new FormData();
        formData.append('csv_file', this.file);
        
        try {
            const response = await fetch('{{ route("admin.books.import") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (response.ok) {
                window.location.href = '{{ route("admin.books.index") }}';
                alert(result.message);
            } else {
                alert(result.message || 'failed to import');
                this.uploading = false;
            }
        } catch (error) {
            alert('Error uploading file');
            this.uploading = false;
        }
    }
}">
    <div class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-black/50 p-4">
        <div class="relative w-full max-w-2xl rounded-2xl bg-white shadow-xl dark:bg-gray-900">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-800">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">Import Books from CSV</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload a CSV file to bulk import books</p>
                </div>
                <button @click="$dispatch('close-modal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Instructions -->
                <div class="mb-6 rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
                    <h4 class="mb-2 font-semibold text-blue-800 dark:text-blue-300">CSV Format Instructions</h4>
                    <ul class="list-inside list-disc space-y-1 text-sm text-blue-700 dark:text-blue-300">
                        <li>First row must contain column headers</li>
                        <li>Required columns: title, author, price</li>
                        <li>Genres and characters should be separated by pipe (|) character</li>
                        <li>Date format: YYYY-MM-DD</li>
                        <li>Maximum file size: 10MB</li>
                    </ul>
                    <div class="mt-3">
                        <a href="{{ route('admin.books.download-template') }}" 
                           class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download Sample CSV Template
                        </a>
                    </div>
                </div>

                <!-- Dropzone -->
                <div class="transition border border-gray-300 border-dashed cursor-pointer dark:hover:border-brand-500 dark:border-gray-700 rounded-xl hover:border-brand-500">
                    <div 
                        @drop.prevent="handleDrop($event)"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @click="$refs.fileInput.click()"
                        :class="isDragging ? 'border-brand-500 bg-gray-100 dark:bg-gray-800' : 'border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-900'"
                        class="dropzone rounded-xl border-dashed border-gray-300 p-7 lg:p-10 transition-colors cursor-pointer">
                        
                        <input x-ref="fileInput" type="file" @change="handleFile($event.target.files[0])" accept=".csv" class="hidden" />

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
                                <span x-show="!isDragging && !fileName">Drag & Drop CSV File Here</span>
                                <span x-show="isDragging" x-cloak>Drop CSV File Here</span>
                                <span x-show="fileName && !isDragging" x-cloak x-text="fileName"></span>
                            </h4>

                            <span class="text-center mb-5 block w-full max-w-[290px] text-sm text-gray-700 dark:text-gray-400">
                                <span x-show="!fileName">Drag and drop your CSV file here or browse</span>
                                <span x-show="fileName" x-cloak>Ready to import</span>
                            </span>

                            <span class="font-medium underline text-theme-sm text-brand-500">
                                Browse File
                            </span>
                        </div>
                    </div>

                    <!-- File Preview -->
                    <div x-show="fileName" class="mt-4 p-4 border-t border-gray-200 dark:border-gray-700" x-cloak>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300" x-text="fileName"></span>
                            </div>
                            <button @click.stop="removeFile()" type="button" class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Warning Message -->
                <div class="mt-4 rounded-lg bg-yellow-50 p-3 dark:bg-yellow-900/20">
                    <p class="text-sm text-yellow-800 dark:text-yellow-300">
                        <strong>Note:</strong> Books with existing ISBN or same title+author will be skipped to prevent duplicates.
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-3 border-t border-gray-200 p-4 dark:border-gray-800">
                <button @click="$dispatch('close-modal')" 
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                    Cancel
                </button>
                <button @click="submitForm" 
                        :disabled="uploading"
                        class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!uploading">Import Books</span>
                    <span x-show="uploading" x-cloak>
                        <svg class="inline h-4 w-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Importing...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>