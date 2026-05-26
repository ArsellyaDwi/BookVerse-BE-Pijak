@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="AI Emotion Rules Management" />

    <div class="space-y-6">
        <x-common.component-card title="Emotion Rules Configuration" desc="Configure output emotions for each input emotion">
            
            <form id="emotionRulesForm" method="POST">
                @csrf
                <div class="space-y-6">
                    @foreach($availableEmotions as $index => $emotion)
                        @php
                            $existingRule = $rules->get($emotion);
                            $selectedOutputs = $existingRule ? $existingRule->suggested_output_emotions : [];
                            $matchRatio = $existingRule ? $existingRule->suggested_match_ratio : 50;
                            $matchedOutputs = $existingRule ? $existingRule->matched_output_emotions : [];
                        @endphp
                        
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <!-- Input Emotion -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Input Emotion
                                    </label>
                                    <div class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <span class="text-base font-semibold text-gray-900 dark:text-white">
                                            {{ ucfirst($emotion) }}
                                        </span>
                                    </div>
                                    <input type="hidden" name="rules[{{ $index }}][input_emotion]" value="{{ $emotion }}">
                                </div>

                                <!-- Ratio Input -->
                                <div x-data="{ ratio: {{ $matchRatio }} }">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Ratio (%)
                                    </label>
                                    <input type="number" 
                                           name="rules[{{ $index }}][suggested_match_ratio]"
                                           x-model="ratio"
                                           step="1"
                                           min="0"
                                           max="100"
                                           class="shadow-theme-xs w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <p class="text-theme-xs text-gray-500 mt-1.5">
                                        Suggested: <span x-text="ratio"></span>% | Matched: <span x-text="100 - ratio"></span>%
                                    </p>
                                </div>

                                <!-- Suggested Output Emotions (Alpine.js Multi-Select) -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Suggested Output Emotions
                                    </label>
                                    <div x-data="{
                                        open: false,
                                        selected: {{ json_encode($selectedOutputs) }},
                                        options: {{ json_encode($availableEmotions) }},
                                        toggleOption(emotion) {
                                            if (this.selected.includes(emotion)) {
                                                this.selected = this.selected.filter(e => e !== emotion);
                                            } else {
                                                this.selected.push(emotion);
                                            }
                                        },
                                        isSelected(emotion) {
                                            return this.selected.includes(emotion);
                                        },
                                        removeEmotion(emotion) {
                                            this.selected = this.selected.filter(e => e !== emotion);
                                        }
                                    }" class="relative" @click.away="open = false">
                                        <template x-for="emotion in selected" :key="emotion">
                                            <input type="hidden" name="rules[{{ $index }}][suggested_output_emotions][]" :value="emotion">
                                        </template>

                                        <div @click="open = !open"
                                            class="shadow-theme-xs flex min-h-11 cursor-pointer gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 transition dark:border-gray-700 dark:bg-gray-900">
                                            <div class="flex flex-1 flex-wrap items-center gap-2">
                                                <template x-for="emotion in selected" :key="emotion">
                                                    <div class="group flex items-center justify-center rounded-full border-[0.7px] border-transparent bg-brand-50 py-1 pr-2 pl-2.5 text-sm text-brand-700 hover:border-brand-200 dark:bg-brand-500/10 dark:text-brand-400">
                                                        <span x-text="emotion.charAt(0).toUpperCase() + emotion.slice(1)"></span>
                                                        <button type="button" @click.stop="removeEmotion(emotion)"
                                                            class="ml-1 text-brand-500 hover:text-brand-700 dark:text-brand-400">
                                                            <svg class="fill-current" width="14" height="14" viewBox="0 0 14 14">
                                                                <path fill-rule="evenodd" d="M3.40717 4.46881C3.11428 4.17591 3.11428 3.70104 3.40717 3.40815C3.70006 3.11525 4.17494 3.11525 4.46783 3.40815L6.99943 5.93975L9.53095 3.40822C9.82385 3.11533 10.2987 3.11533 10.5916 3.40822C10.8845 3.70112 10.8845 4.17599 10.5916 4.46888L8.06009 7.00041L10.5916 9.53193C10.8845 9.82482 10.8845 10.2997 10.5916 10.5926C10.2987 10.8855 9.82385 10.8855 9.53095 10.5926L6.99943 8.06107L4.46783 10.5927C4.17494 10.8856 3.70006 10.8856 3.40717 10.5927C3.11428 10.2998 3.11428 9.8249 3.40717 9.53201L5.93877 7.00041L3.40717 4.46881Z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <span x-show="selected.length === 0" class="text-sm text-gray-500 dark:text-gray-400">
                                                    Select suggested emotions...
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
                                                <template x-for="emotion in options" :key="emotion">
                                                    <div @click="toggleOption(emotion)"
                                                        class="cursor-pointer border-b border-gray-200 px-4 py-3 text-sm transition last:border-b-0 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-gray-800 dark:text-white/90" x-text="emotion.charAt(0).toUpperCase() + emotion.slice(1)"></span>
                                                            <svg x-show="isSelected(emotion)" class="h-5 w-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Matched Output Emotions (Alpine.js Multi-Select) -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Matched Output Emotions
                                    </label>
                                    <div x-data="{
                                        openMatched: false,
                                        selectedMatched: {{ json_encode($matchedOutputs) }},
                                        options: {{ json_encode($availableEmotions) }},
                                        toggleMatchedOption(emotion) {
                                            if (this.selectedMatched.includes(emotion)) {
                                                this.selectedMatched = this.selectedMatched.filter(e => e !== emotion);
                                            } else {
                                                this.selectedMatched.push(emotion);
                                            }
                                        },
                                        isMatchedSelected(emotion) {
                                            return this.selectedMatched.includes(emotion);
                                        },
                                        removeMatchedEmotion(emotion) {
                                            this.selectedMatched = this.selectedMatched.filter(e => e !== emotion);
                                        }
                                    }" class="relative" @click.away="openMatched = false">
                                        <template x-for="emotion in selectedMatched" :key="emotion">
                                            <input type="hidden" name="rules[{{ $index }}][matched_output_emotions][]" :value="emotion">
                                        </template>

                                        <div @click="openMatched = !openMatched"
                                            class="shadow-theme-xs flex min-h-11 cursor-pointer gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 transition dark:border-gray-700 dark:bg-gray-900">
                                            <div class="flex flex-1 flex-wrap items-center gap-2">
                                                <template x-for="emotion in selectedMatched" :key="emotion">
                                                    <div class="group flex items-center justify-center rounded-full border-[0.7px] border-transparent bg-blue-50 py-1 pr-2 pl-2.5 text-sm text-blue-700 hover:border-blue-200 dark:bg-blue-500/10 dark:text-blue-400">
                                                        <span x-text="emotion.charAt(0).toUpperCase() + emotion.slice(1)"></span>
                                                        <button type="button" @click.stop="removeMatchedEmotion(emotion)"
                                                            class="ml-1 text-blue-500 hover:text-blue-700 dark:text-blue-400">
                                                            <svg class="fill-current" width="14" height="14" viewBox="0 0 14 14">
                                                                <path fill-rule="evenodd" d="M3.40717 4.46881C3.11428 4.17591 3.11428 3.70104 3.40717 3.40815C3.70006 3.11525 4.17494 3.11525 4.46783 3.40815L6.99943 5.93975L9.53095 3.40822C9.82385 3.11533 10.2987 3.11533 10.5916 3.40822C10.8845 3.70112 10.8845 4.17599 10.5916 4.46888L8.06009 7.00041L10.5916 9.53193C10.8845 9.82482 10.8845 10.2997 10.5916 10.5926C10.2987 10.8855 9.82385 10.8855 9.53095 10.5926L6.99943 8.06107L4.46783 10.5927C4.17494 10.8856 3.70006 10.8856 3.40717 10.5927C3.11428 10.2998 3.11428 9.8249 3.40717 9.53201L5.93877 7.00041L3.40717 4.46881Z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <span x-show="selectedMatched.length === 0" class="text-sm text-gray-500 dark:text-gray-400">
                                                    Select matched emotions...
                                                </span>
                                            </div>
                                            <div class="flex items-start pt-1.5">
                                                <svg class="h-5 w-5 shrink-0 text-gray-500 transition-transform dark:text-gray-400"
                                                    :class="openMatched ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>

                                        <div x-show="openMatched" x-cloak
                                            class="absolute z-50 mt-1 w-full overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900"
                                            style="max-height: 16rem">
                                            <div class="overflow-y-auto" style="max-height: 16rem">
                                                <template x-for="emotion in options" :key="emotion">
                                                    <div @click="toggleMatchedOption(emotion)"
                                                        class="cursor-pointer border-b border-gray-200 px-4 py-3 text-sm transition last:border-b-0 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-gray-800 dark:text-white/90" x-text="emotion.charAt(0).toUpperCase() + emotion.slice(1)"></span>
                                                            <svg x-show="isMatchedSelected(emotion)" class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="resetAllForms()"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Reset All
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Save All Rules
                    </button>
                </div>
            </form>
        </x-common.component-card>

        <!-- Summary Card -->
        <x-common.component-card title="Rules Summary" desc="Overview of all emotion rules">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Emotions</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($availableEmotions) }}</div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Rules Configured</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rules->count() }}</div>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Pending Configuration</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($availableEmotions) - $rules->count() }}</div>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Mappings</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $rules->sum(function($rule) { return count($rule->suggested_output_emotions ?? []) + count($rule->matched_output_emotions ?? []); }) }}
                    </div>
                </div>
            </div>
        </x-common.component-card>
    </div>
</div>

@push('scripts')
<script>
// Handle form submission
document.getElementById('emotionRulesForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const rules = [];
    
    // Get all unique indices from form data
    const indices = new Set();
    for (let pair of formData.entries()) {
        const matches = pair[0].match(/rules\[(\d+)\]/);
        if (matches) {
            indices.add(parseInt(matches[1]));
        }
    }
    
    // Build rules array
    indices.forEach(index => {
        const rule = {
            input_emotion: formData.get(`rules[${index}][input_emotion]`),
            suggested_match_ratio: parseInt(formData.get(`rules[${index}][suggested_match_ratio]`)) || 50,
            suggested_output_emotions: formData.getAll(`rules[${index}][suggested_output_emotions][]`),
            matched_output_emotions: formData.getAll(`rules[${index}][matched_output_emotions][]`)
        };
        rules.push(rule);
    });
    
    const submitData = { rules: rules };
    
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('{{ route("admin.ai.emotion-rules.bulk-update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(submitData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            
            // Reload after 1.5 seconds to show updated summary
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification(data.message || 'An error occurred', 'error');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred while saving the rules', 'error');
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

// Reset all forms
function resetAllForms() {
    if (confirm('Are you sure you want to reset all unsaved changes? This will reload the page.')) {
        location.reload();
    }
}

// Notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' 
            ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800'
            : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800'
    }`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="${type === 'success' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'}"/>
            </svg>
            <span class="text-sm font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endpush
@endsection