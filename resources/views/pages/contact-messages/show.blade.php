@extends('layouts.app')

@section('content')
<div class="">
    <x-common.page-breadcrumb pageTitle="Message Details" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Message Content --}}
        <div class="lg:col-span-2">
            <x-common.component-card title="Customer Message" desc="View customer inquiry details">
                <div class="space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-xs text-gray-500">From</p>
                                <p class="font-medium text-gray-900">{{ $message->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="font-medium text-gray-900">{{ $message->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Subject</p>
                                <p class="font-medium text-gray-900">{{ $message->subject }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Received</p>
                                <p class="font-medium text-gray-900">{{ $message->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-2">Message</p>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $message->message }}</p>
                        </div>
                    </div>

                    @if($message->admin_reply)
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200">
                            <p class="text-xs text-green-600 mb-2">Your Reply</p>
                            <p class="text-gray-700">{{ $message->admin_reply }}</p>
                            <p class="text-xs text-gray-500 mt-2">
                                Replied on: {{ $message->replied_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    @endif
                </div>
            </x-common.component-card>
        </div>

        {{-- Reply Form --}}
        <div>
            <x-common.component-card title="Reply to Customer" desc="Send response to customer">
                <form action="{{ route('admin.contact-messages.reply', $message) }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                Your Reply <span class="text-red-500">*</span>
                            </label>
                            <textarea name="reply" 
                                      rows="8"
                                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500"
                                      placeholder="Write your reply here..."
                                      required>{{ old('reply') }}</textarea>
                        </div>

                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong>Note:</strong> Your reply will be marked as replied and the customer will receive an email notification.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" 
                                    class="flex-1 bg-brand-500 text-white px-4 py-2.5 rounded-lg hover:bg-brand-600 transition-colors">
                                Send Reply
                            </button>
                            <a href="{{ route('admin.contact-messages.index') }}" 
                               class="px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Back
                            </a>
                        </div>
                    </div>
                </form>
            </x-common.component-card>
        </div>
    </div>
</div>
@endsection