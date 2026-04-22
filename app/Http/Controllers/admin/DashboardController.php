<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\AiRecommendationLog;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current month and previous month dates
        $currentMonth = now()->startOfMonth();
        $previousMonth = now()->subMonth()->startOfMonth();

        // Transaction Statistics
        $totalRevenue = Transaction::where('status', '!=', 'cancelled')->sum('total');
        $totalOrders = Transaction::count();
        $pendingTransactions = Transaction::where('status', 'waiting_payment')->count();
        $completedOrders = Transaction::where('status', 'done')->count();
        $shippedOrders = Transaction::where('status', 'shipped')->count();
        $cancelledOrders = Transaction::where('status', 'cancelled')->count();

        // Monthly Revenue
        $monthlyRevenue = Transaction::where('status', '!=', 'cancelled')
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('total');

        $previousMonthRevenue = Transaction::where('status', '!=', 'cancelled')
            ->whereYear('date', now()->subMonth()->year)
            ->whereMonth('date', now()->subMonth()->month)
            ->sum('total');

        $revenueGrowth = $previousMonthRevenue > 0
            ? (($monthlyRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100
            : ($monthlyRevenue > 0 ? 100 : 0);

        // AI Recommendation Statistics
        $totalRecommendations = AiRecommendationLog::count();
        $uniqueUsersRecommended = AiRecommendationLog::distinct('user_id')->count('user_id');
        $avgRecommendationsPerUser = $totalRecommendations > 0
            ? round($totalRecommendations / $uniqueUsersRecommended, 1)
            : 0;

        $recommendationsThisMonth = AiRecommendationLog::whereMonth('create_at', now()->month)
            ->whereYear('create_at', now()->year)
            ->count();

        $recommendationsLastMonth = AiRecommendationLog::whereMonth('create_at', now()->subMonth()->month)
            ->whereYear('create_at', now()->subMonth()->year)
            ->count();

        $recommendationGrowth = $recommendationsLastMonth > 0
            ? (($recommendationsThisMonth - $recommendationsLastMonth) / $recommendationsLastMonth) * 100
            : ($recommendationsThisMonth > 0 ? 100 : 0);

        // Get popular recommended books
        $popularBooks = DB::table('ai_recommendation_items')
            ->join('books', 'ai_recommendation_items.book_id', '=', 'books.id')
            ->select('books.id', 'books.title', 'books.author', DB::raw('count(*) as recommendation_count'))
            ->groupBy('books.id', 'books.title', 'books.author')
            ->orderBy('recommendation_count', 'desc')
            ->limit(5)
            ->get();

        // Customer Statistics
        $totalCustomers = User::where('role', '=', 'customer')->count();
        $newCustomersThisMonth = User::where('role', '=', 'customer')->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Book Statistics
        $totalBooks = Book::count();
        $lowStockBooks = Book::where('stock', '<=', 5)->where('stock', '>', 0)->count();
        $outOfStockBooks = Book::where('stock', 0)->count();

        // Recent Transactions
        $recentTransactions = Transaction::with('user')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        // Recent AI Recommendations
        $recentRecommendations = AiRecommendationLog::with('user')
            ->orderBy('create_at', 'desc')
            ->limit(10)
            ->get();

        // Chart Data - Last 7 days revenue
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('D');

            $dailyRevenue = Transaction::where('status', '!=', 'cancelled')
                ->whereDate('date', $date->toDateString())
                ->sum('total');
            $chartData[] = $dailyRevenue;
        }

        // Chart Data - Recommendations per day (last 7 days)
        $recChartLabels = [];
        $recChartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $recChartLabels[] = $date->format('D');

            $dailyRecs = AiRecommendationLog::whereDate('create_at', $date->toDateString())->count();
            $recChartData[] = $dailyRecs;
        }

        return view('pages.dashboard.index', compact(
            'totalRevenue',
            'totalOrders',
            'pendingTransactions',
            'completedOrders',
            'shippedOrders',
            'cancelledOrders',
            'monthlyRevenue',
            'revenueGrowth',
            'totalRecommendations',
            'uniqueUsersRecommended',
            'avgRecommendationsPerUser',
            'recommendationsThisMonth',
            'recommendationGrowth',
            'popularBooks',
            'totalCustomers',
            'newCustomersThisMonth',
            'totalBooks',
            'lowStockBooks',
            'outOfStockBooks',
            'recentTransactions',
            'recentRecommendations',
            'chartLabels',
            'chartData',
            'recChartLabels',
            'recChartData'
        ));
    }
}
