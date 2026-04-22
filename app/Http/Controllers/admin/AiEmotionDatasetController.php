<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AiEmotionDataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiEmotionDatasetController extends Controller
{
    /**
     * Display a listing of the datasets.
     */
    public function index(Request $request)
    {
        $query = AiEmotionDataset::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('labels', 'like', "%{$search}%")
                    ->orWhere('text', 'like', "%{$search}%");
            });
        }

        $datasets = $query->orderBy('created_at', 'desc')->paginate(15);
        $datasets->appends(['search' => $request->search]);

        return view('pages.ai.emotion-datasets.index', compact('datasets'));
    }

    /**
     * Show the form for creating a new dataset.
     */
    public function create()
    {
        return view('pages.ai.emotion-datasets.create');
    }

    /**
     * Store a newly created dataset in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'labels' => 'required|string|max:255',
            'text' => 'required|string',
        ]);

        AiEmotionDataset::create($validated);

        return redirect()->route('admin.ai.emotion-datasets.index')
            ->with('success', 'Emotion dataset created successfully!');
    }

    /**
     * Display the specified dataset.
     */
    public function show(AiEmotionDataset $aiEmotionDataset)
    {
        return view('pages.ai.emotion-datasets.show', compact('aiEmotionDataset'));
    }

    /**
     * Show the form for editing the specified dataset.
     */
    public function edit(AiEmotionDataset $aiEmotionDataset)
    {
        return view('pages.ai.emotion-datasets.edit', compact('aiEmotionDataset'));
    }

    /**
     * Update the specified dataset in storage.
     */
    public function update(Request $request, AiEmotionDataset $aiEmotionDataset)
    {
        $validated = $request->validate([
            'labels' => 'required|string|max:255',
            'text' => 'required|string',
        ]);

        $aiEmotionDataset->update($validated);

        return redirect()->route('admin.ai.emotion-datasets.index')
            ->with('success', 'Emotion dataset updated successfully!');
    }

    /**
     * Remove the specified dataset from storage.
     */
    public function destroy(AiEmotionDataset $aiEmotionDataset)
    {
        $aiEmotionDataset->delete();

        return redirect()->route('admin.ai.emotion-datasets.index')
            ->with('success', 'Emotion dataset deleted successfully!');
    }

    /**
     * Show the import modal view
     */
    public function showImportForm()
    {
        return view('pages.ai.emotion-datasets.import');
    }

    /**
     * Handle CSV import
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        // Open CSV file
        $handle = fopen($path, 'r');
        if (!$handle) {
            return response()->json(['error' => 'Cannot read the CSV file.'], 400);
        }

        // Get headers
        $headers = fgetcsv($handle, 1000, ',');
        if (!$headers) {
            fclose($handle);
            return response()->json(['error' => 'Empty CSV file or invalid format.'], 400);
        }

        // Expected headers
        $expectedHeaders = ['labels', 'text'];
        $requiredHeaders = ['labels', 'text'];

        // Check if required columns exist
        $missingRequired = array_diff($requiredHeaders, $headers);
        if (!empty($missingRequired)) {
            fclose($handle);
            return response()->json(['error' => 'Missing required columns: ' . implode(', ', $missingRequired)], 400);
        }

        $imported = 0;
        $skipped = 0;
        $skippedRows = [];
        $rowNumber = 1; // Start after header

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rowNumber++;

                // Skip if row has fewer columns than headers
                if (count($row) < count($requiredHeaders)) {
                    $skipped++;
                    $skippedRows[] = $rowNumber;
                    continue;
                }

                // Combine headers with row data
                $data = array_combine($headers, array_pad($row, count($headers), ''));

                // Validate required fields
                if (empty($data['labels']) || empty($data['text'])) {
                    $skipped++;
                    $skippedRows[] = $rowNumber;
                    continue;
                }

                // Check if dataset already exists (optional - based on labels and text)
                $existing = AiEmotionDataset::where('labels', $data['labels'])
                    ->where('text', $data['text'])
                    ->first();

                if ($existing) {
                    $skipped++;
                    $skippedRows[] = $rowNumber;
                    continue;
                }

                // Create dataset
                try {
                    AiEmotionDataset::create([
                        'labels' => trim($data['labels']),
                        'text' => trim($data['text']),
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $skipped++;
                    $skippedRows[] = $rowNumber;
                    continue;
                }
            }

            DB::commit();
            fclose($handle);

            $message = "Successfully imported {$imported} emotion datasets.";
            if ($skipped > 0) {
                $message .= " Skipped {$skipped} rows";
                if (count($skippedRows) > 0) {
                    $message .= " (Rows: " . implode(', ', array_slice($skippedRows, 0, 10));
                    if (count($skippedRows) > 10) {
                        $message .= "... and " . (count($skippedRows) - 10) . " more";
                    }
                    $message .= ")";
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'imported' => $imported,
                'skipped' => $skipped
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return response()->json(['error' => 'Error importing CSV: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download sample CSV template
     */
    public function downloadTemplate()
    {
        $headers = ['labels', 'text'];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            // Add sample rows
            fputcsv($file, ['happy', 'I am so excited about this wonderful news!']);
            fputcsv($file, ['sad', 'I feel so down and depressed today.']);
            fputcsv($file, ['angry', 'This is absolutely infuriating and unacceptable!']);
            fputcsv($file, ['fear', 'I am terrified about what might happen next.']);
            fputcsv($file, ['surprise', 'Wow! I never expected this to happen!']);
            fputcsv($file, ['love', 'I absolutely adore spending time with my family.']);
            fputcsv($file, ['neutral', 'The weather is okay today.']);

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="emotion_datasets_template.csv"',
        ]);
    }

    /**
     * Bulk delete datasets
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:ai_emotion_datasets,id',
        ]);

        AiEmotionDataset::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.ai.emotion-datasets.index')
            ->with('success', 'Selected datasets deleted successfully!');
    }
}
