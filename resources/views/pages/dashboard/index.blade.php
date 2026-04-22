@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <x-common.page-breadcrumb pageTitle="Dashboard" />

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Revenue -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    @if($revenueGrowth != 0)
                        <p class="text-xs {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                            {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ number_format(abs($revenueGrowth), 1) }}% vs last month
                        </p>
                    @endif
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Orders</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($totalOrders) }}</p>
                    <div class="flex gap-2 mt-1">
                        <span class="text-xs text-green-600">✓ {{ $completedOrders }} done</span>
                        <span class="text-xs text-blue-600">📦 {{ $shippedOrders }} shipped</span>
                    </div>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Transactions -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending Payments</p>
                    <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400">{{ number_format($pendingTransactions) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Awaiting confirmation</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/30">
                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- AI Recommendations -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">AI Recommendations</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($totalRecommendations) }}</p>
                    @if($recommendationGrowth != 0)
                        <p class="text-xs {{ $recommendationGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                            {{ $recommendationGrowth >= 0 ? '↑' : '↓' }} {{ number_format(abs($recommendationGrowth), 1) }}% vs last month
                        </p>
                    @endif
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30">
                    <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row Statistics -->
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Customers -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Customers</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($totalCustomers) }}</p>
                    <p class="text-xs text-green-600 mt-1">+{{ $newCustomersThisMonth }} this month</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/30">
                    <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Books -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Books</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($totalBooks) }}</p>
                    <div class="flex gap-2 mt-1">
                        @if($lowStockBooks > 0)
                            <span class="text-xs text-orange-600">⚠️ {{ $lowStockBooks }} low stock</span>
                        @endif
                        @if($outOfStockBooks > 0)
                            <span class="text-xs text-red-600">❌ {{ $outOfStockBooks }} out of stock</span>
                        @endif
                    </div>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-cyan-100 dark:bg-cyan-900/30">
                    <svg class="h-6 w-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- AI Users -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Users Using AI</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($uniqueUsersRecommended) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $avgRecommendationsPerUser }} avg per user</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-pink-100 dark:bg-pink-900/30">
                    <svg class="h-6 w-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">This Month Revenue</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $recommendationsThisMonth }} AI requests</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900/30">
                    <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
        <!-- Revenue Chart -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Revenue (Last 7 Days)</h3>
            <canvas id="revenueChart" class="w-full h-64"></canvas>
        </div>

        <!-- AI Recommendations Chart -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">AI Recommendations (Last 7 Days)</h3>
            <canvas id="recommendationsChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <!-- Popular Books from AI Recommendations -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 mb-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-5 mb-4 sm:px-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Most Recommended Books by AI</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Top books that customers are being recommended</p>
        </div>
        <div class="overflow-hidden">
            <div class="max-w-full px-5 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-gray-200 border-y dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">#</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Book Title</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Author</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Times Recommended</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($popularBooks as $index => $book)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $book->title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $book->author }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                    {{ $book->recommendation_count }} times
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No recommendation data available yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Transactions & AI Recommendations -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent Transactions -->
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 mb-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Recent Transactions</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Latest customer orders</p>
                    </div>
                    <a href="{{ route('admin.transactions.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        View All →
                    </a>
                </div>
            </div>
            <div class="overflow-hidden">
                <div class="max-w-full px-5 overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-gray-200 border-y dark:border-gray-700">
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">ID</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Customer</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Date</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Total</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">#{{ $transaction->id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $transaction->user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $transaction->date->format('M d, H:i') }}</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $statusColors = [
                                            'waiting_payment' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                            'shipped' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                            'done' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$transaction->status] }}">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No transactions found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent AI Recommendations -->
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 mb-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Recent AI Recommendations</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Latest AI book suggestions</p>
                    </div>
                    <a href="{{ route('admin.ai.recommendation-logs.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        View All →
                    </a>
                </div>
            </div>
            <div class="overflow-hidden">
                <div class="max-w-full px-5 overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-gray-200 border-y dark:border-gray-700">
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">User</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Input</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Books</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentRecommendations as $rec)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $rec->user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                    {{ Str::limit($rec->input, 40) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                        {{ $rec->books->count() }} books
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $rec->create_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No AI recommendations yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Revenue (Rp)',
                data: @json($chartData),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // AI Recommendations Chart
    const recCtx = document.getElementById('recommendationsChart').getContext('2d');
    new Chart(recCtx, {
        type: 'bar',
        data: {
            labels: @json($recChartLabels),
            datasets: [{
                label: 'Recommendations',
                data: @json($recChartData),
                backgroundColor: '#8b5cf6',
                borderRadius: 8,
                barPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection