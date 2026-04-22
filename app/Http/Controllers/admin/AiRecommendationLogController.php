<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AiRecommendationLog;
use Illuminate\Http\Request;

class AiRecommendationLogController extends Controller
{
    /**
     * Display a listing of recommendation logs.
     */
    public function index(Request $request)
    {
        $query = AiRecommendationLog::with('user', 'books');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('input', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by date
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('create_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('create_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('create_at', 'desc')->paginate(15);
        $logs->appends($request->all());

        // Get statistics
        $totalRecommendations = AiRecommendationLog::count();
        $totalUsers = AiRecommendationLog::distinct('user_id')->count('user_id');
        $averageRecommendations = AiRecommendationLog::withCount('recommendationItems')->get()->avg('recommendation_items_count') ?? 0;
        $latestRecommendation = AiRecommendationLog::latest('create_at')->first();

        return view('pages.ai.recommendation-logs.index', compact('logs', 'totalRecommendations', 'totalUsers', 'averageRecommendations', 'latestRecommendation'));
    }

    /**
     * Display the specified recommendation log.
     */
    public function show(AiRecommendationLog $aiRecommendationLog)
    {
        $aiRecommendationLog->load('user', 'books', 'recommendationItems.book');
        return view('pages.ai.recommendation-logs.show', compact('aiRecommendationLog'));
    }

    /**
     * Remove the specified recommendation log.
     */
    public function destroy(AiRecommendationLog $aiRecommendationLog)
    {
        $aiRecommendationLog->delete();

        return redirect()->route('admin.ai.recommendation-logs.index')
            ->with('success', 'Recommendation log deleted successfully!');
    }

    /**
     * Clear all recommendation logs.
     */
    public function clearAll()
    {
        AiRecommendationLog::truncate();

        return redirect()->route('admin.ai.recommendation-logs.index')
            ->with('success', 'All recommendation logs cleared successfully!');
    }

    /**
     * Export recommendation logs to CSV
     */
    public function export(Request $request)
    {
        $query = AiRecommendationLog::with('user', 'books');

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('create_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('create_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('create_at', 'desc')->get();

        $filename = 'recommendation_logs_' . date('Y-m-d_H-i-s') . '.csv';

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['ID', 'User Name', 'User Email', 'Input Text', 'Number of Recommendations', 'Books Recommended', 'Created At']);

            // Add data rows
            foreach ($logs as $log) {
                $bookTitles = $log->books->pluck('title')->implode(' | ');

                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'N/A',
                    $log->user->email ?? 'N/A',
                    $log->input,
                    $log->books->count(),
                    $bookTitles,
                    $log->create_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
