<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AiTrainingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AiTrainingLogController extends Controller
{
    /**
     * Display a listing of training logs.
     */
    public function index(Request $request)
    {
        $query = AiTrainingLog::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('total_loss', 'like', "%{$search}%")
                    ->orWhere('total_time', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(15);
        $logs->appends(['search' => $request->search]);

        // Get latest training stats
        $latestLog = AiTrainingLog::latest()->first();
        $averageLoss = AiTrainingLog::avg('total_loss');
        $averageTime = AiTrainingLog::avg('total_time');
        $totalTrainings = AiTrainingLog::count();

        return view('pages.ai.training-logs.index', compact('logs', 'latestLog', 'averageLoss', 'averageTime', 'totalTrainings'));
    }

    /**
     * Start retraining process.
     */
    public function retrain(Request $request)
    {
        // Validate request if needed
        $request->validate([
            'epochs' => 'nullable|integer|min:1|max:100',
            'batch_size' => 'nullable|integer|min:1|max:256',
        ]);

        try {
            // Here you would dispatch a job or call your AI training service
            // For now, we'll simulate the training process

            // Example: dispatch job to background
            // ProcessRetraining::dispatch($request->all());

            // Or simulate training
            $epochs = $request->epochs ?? 10;
            $batchSize = $request->batch_size ?? 32;

            // Simulate training time and loss (replace with actual AI training logic)
            $startTime = microtime(true);

            // Call your actual training function here
            // $result = $this->runTraining($epochs, $batchSize);

            // Simulate training results
            $totalLoss = rand(10, 100) / 10; // Random loss between 1.0 and 10.0
            $totalTime = microtime(true) - $startTime;

            // Save training log
            AiTrainingLog::create([
                'total_loss' => $totalLoss,
                'total_time' => $totalTime,
            ]);

            return redirect()->route('admin.ai.training-logs.index')
                ->with('success', 'Retraining completed successfully! Loss: ' . number_format($totalLoss, 4) . ', Time: ' . number_format($totalTime, 2) . 's');
        } catch (\Exception $e) {
            Log::error('Training failed: ' . $e->getMessage());
            return redirect()->route('admin.ai.training-logs.index')
                ->with('error', 'Retraining failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove training log.
     */
    public function destroy(AiTrainingLog $aiTrainingLog)
    {
        $aiTrainingLog->delete();

        return redirect()->route('admin.ai.training-logs.index')
            ->with('success', 'Training log deleted successfully!');
    }

    /**
     * Clear all training logs.
     */
    public function clearAll()
    {
        AiTrainingLog::truncate();

        return redirect()->route('admin.ai.training-logs.index')
            ->with('success', 'All training logs cleared successfully!');
    }
}
