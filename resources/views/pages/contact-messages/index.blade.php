{{-- resources/views/pages/contact-messages/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <x-common.page-breadcrumb pageTitle="Contact Messages" />

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Messages -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Messages</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($totalMessages ?? $messages->total()) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Unread Messages -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Unread Messages</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($unreadCount) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Read Messages -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Read Messages</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($readCount ?? 0) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/30">
                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Replied Messages -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Replied Messages</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($repliedCount ?? 0) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Customer Messages</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage and reply to customer inquiries</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="flex flex-wrap gap-2">
                    <select name="status" class="h-[42px] rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">All Status</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                        <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
                    </select>
                    
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}"
                           class="h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                           placeholder="From">
                    
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}"
                           class="h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                           placeholder="To">
                    
                    <button type="submit" 
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-500 px-3 py-2 text-sm font-medium text-white hover:bg-gray-600">
                        Filter
                    </button>
                    @if(request('status') || request('date_from') || request('date_to') || request('search'))
                        <a href="{{ route('admin.contact-messages.index') }}" 
                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-500 px-3 py-2 text-sm font-medium text-white hover:bg-gray-600">
                            Clear
                        </a>
                    @endif
                </form>

                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="relative">
                    <button type="submit" class="absolute -translate-y-1/2 left-4 top-1/2">
                        <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                        </svg>
                    </button>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by name, email, or subject..." 
                           class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 xl:w-[250px]"/>
                </form>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mx-5 mb-4 sm:mx-6">
                <div class="rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-300">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Bulk Delete Button -->
        <div class="px-5 mb-3 sm:px-6">
            <button type="button" id="deleteSelectedBtn" 
                    class="hidden inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete Selected
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-hidden">
            <div class="max-w-full px-5 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-gray-200 border-y dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 w-10">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Name</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Email</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Subject</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Date</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($messages as $message)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors {{ $message->status == 'unread' ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <input type="checkbox" class="message-checkbox rounded border-gray-300" value="{{ $message->id }}">
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'unread' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        'read' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                        'replied' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                    ];
                                    $statusLabels = [
                                        'unread' => 'Unread',
                                        'read' => 'Read',
                                        'replied' => 'Replied',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$message->status] }}">
                                    {{ $statusLabels[$message->status] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $message->name }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700 dark:text-gray-300">{{ $message->email }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">{{ $message->subject }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700 dark:text-gray-300">{{ $message->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.contact-messages.show', $message->id) }}" 
                                       class="p-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                       title="View & Reply">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.contact-messages.destroy', $message->id) }}" 
                                          onsubmit="return confirm('Are you sure you want to delete this message?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                title="Delete Message">
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
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No messages found</p>
                                    <p class="text-sm">Messages from customers will appear here</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($messages->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Select All functionality
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.message-checkbox');
    const deleteBtn = document.getElementById('deleteSelectedBtn');

    function toggleDeleteButton() {
        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
        if (deleteBtn) {
            deleteBtn.classList.toggle('hidden', !anyChecked);
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleDeleteButton();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', toggleDeleteButton);
    });

    // Bulk Delete
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            const selectedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selectedIds.length === 0) return;
            
            if (confirm(`Delete ${selectedIds.length} message(s)? This action cannot be undone.`)) {
                fetch('{{ route("admin.contact-messages.bulk-delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: selectedIds })
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          window.location.reload();
                      }
                  });
            }
        });
    }
</script>
@endpush
@endsection