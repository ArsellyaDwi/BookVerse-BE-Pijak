<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AiRecommendationLog;
use Illuminate\Http\Request;

class AiRecommendationLogController extends Controller
{
    /**
     * Display a listing of emotion analysis logs.
     */
    public function index(Request $request)
    {
        $query = AiRecommendationLog::with('user');

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
        $totalAnalyses = AiRecommendationLog::count();
        $totalUsers = AiRecommendationLog::whereNotNull('user_id')->distinct('user_id')->count('user_id');

        // Calculate average emotions per analysis
        $allResults = AiRecommendationLog::whereNotNull('result')->get(['result']);
        $totalEmotions = 0;
        foreach ($allResults as $result) {
            $emotions = is_array($result->result) ? $result->result : [];
            $totalEmotions += count($emotions);
        }
        $averageEmotions = $totalAnalyses > 0 ? round($totalEmotions / $totalAnalyses, 1) : 0;

        $latestAnalysis = AiRecommendationLog::latest('create_at')->first();

        return view('pages.ai.recommendation-logs.index', compact('logs', 'totalAnalyses', 'totalUsers', 'averageEmotions', 'latestAnalysis'));
    }

    /**
     * Display the specified emotion analysis log.
     */
    public function show(string $id)
    {
        $aiRecommendationLog = AiRecommendationLog::find($id)->load('user');
        return view('pages.ai.recommendation-logs.show', compact('aiRecommendationLog'));
    }

    /**
     * Remove the specified emotion analysis log.
     */
    public function destroy(AiRecommendationLog $aiRecommendationLog)
    {
        $aiRecommendationLog->delete();

        return redirect()->route('admin.ai.recommendation-logs.index')
            ->with('success', 'Emotion analysis log deleted successfully!');
    }

    /**
     * Clear all emotion analysis logs.
     */
    public function clearAll()
    {
        AiRecommendationLog::truncate();

        return redirect()->route('admin.ai.recommendation-logs.index')
            ->with('success', 'All emotion analysis logs cleared successfully!');
    }

    /**
     * Export emotion analysis logs to CSV
     */
    public function export(Request $request)
    {
        $query = AiRecommendationLog::with('user');

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('create_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('create_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('create_at', 'desc')->get();

        $filename = 'emotion_analysis_logs_' . date('Y-m-d_H-i-s') . '.csv';

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['ID', 'User Name', 'User Email', 'Input Text', 'Top Emotion', 'Top Confidence', 'All Emotions', 'Created At']);

            // Add data rows
            foreach ($logs as $log) {
                $emotions = is_array($log->result) ? $log->result : [];
                $topEmotion = !empty($emotions) ? $emotions[0] : null;

                $allEmotionsStr = '';
                foreach ($emotions as $emotion) {
                    $allEmotionsStr .= $emotion['emotion'] . ' (' . number_format($emotion['confidence'] * 100, 1) . '%), ';
                }
                $allEmotionsStr = rtrim($allEmotionsStr, ', ');

                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'Guest User',
                    $log->user->email ?? 'N/A',
                    $log->input ?? 'No input',
                    $topEmotion['emotion'] ?? 'N/A',
                    $topEmotion ? number_format($topEmotion['confidence'] * 100, 1) . '%' : 'N/A',
                    $allEmotionsStr ?: 'No emotions',
                    $log->create_at ? $log->create_at->format('Y-m-d H:i:s') : 'N/A',
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
