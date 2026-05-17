<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Character;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('genres');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $books = $query->orderBy('created_at', 'desc')->paginate(10);

        $books->appends(['search' => $request->search]);

        return view('pages.books.index', compact('books'));
    }

    public function create()
    {
        $genres = Genre::all();
        $characters = Character::all();
        return view('pages.books.create', compact('genres', 'characters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'series' => 'nullable|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:100',
            'isbn' => 'nullable|string|unique:books,isbn|max:20',
            'stock' => 'required|integer|min:0',
            'pages' => 'nullable|integer|min:1',
            'publisher' => 'nullable|string|max:255',
            'publish_date' => 'nullable|date',
            'price' => 'required|numeric|min:0',
            'cover_img' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'genres' => 'array',
            'genres.*' => 'exists:genres,id',
            'characters' => 'array',
            'characters.*' => 'exists:characters,id',
        ]);

        if ($request->hasFile('cover_img')) {
            $validated['cover_img'] = $request->file('cover_img')->store('book-covers', 'public');
        }

        $validated['rating'] = 0;
        $validated['ratings'] = 0;

        $book = Book::create($validated);

        if ($request->has('genres')) {
            $book->genres()->attach($request->genres);
        }

        if ($request->has('characters')) {
            $book->characters()->attach($request->characters);
        }

        return redirect()->route('admin.books.index')->with('success', 'Book created successfully!');
    }

    public function show(Book $book)
    {
        $book->load(['genres', 'characters']);
        return view('pages.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $genres = Genre::all();
        $characters = Character::all();
        $book->load(['genres', 'characters']);
        return view('pages.books.edit', compact('book', 'genres', 'characters'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'series' => 'nullable|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:100',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'stock' => 'required|integer|min:0',
            'pages' => 'nullable|integer|min:1',
            'publisher' => 'nullable|string|max:255',
            'publish_date' => 'nullable|date',
            'price' => 'required|numeric|min:0',
            'cover_img' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'genres' => 'array',
            'genres.*' => 'exists:genres,id',
            'characters' => 'array',
            'characters.*' => 'exists:characters,id',
        ]);


        if ($request->hasFile('cover_img')) {
            if ($book->cover_img) {
                Storage::disk('public')->delete($book->cover_img);
            }
            $validated['cover_img'] = $request->file('cover_img')->store('book-covers', 'public');
        }

        $book->update($validated);

        $book->genres()->sync($request->genres ?? []);
        $book->characters()->sync($request->characters ?? []);

        return redirect()->route('admin.books.index')->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        if ($book->cover_img) {
            Storage::disk('public')->delete($book->cover_img);
        }

        $book->genres()->detach();
        $book->characters()->detach();
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully!');
    }


    public function showImportForm()
    {
        return view('pages.books.import');
    }

    private function parseJsonArray($string)
    {
        if (empty($string) || $string === '[]' || $string === '{}') {
            return [];
        }

        try {
            // Try to decode as JSON first
            $decoded = json_decode($string, true);
            if (is_array($decoded)) {
                return $decoded;
            }

            // Handle Python-style list format: "['item1', 'item2']"
            if (strpos($string, "'") !== false) {
                // Replace single quotes with double quotes for JSON
                $jsonString = str_replace("'", '"', $string);
                $decoded = json_decode($jsonString, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }

            // Handle pipe-separated values as fallback
            if (strpos($string, '|') !== false) {
                return array_filter(array_map('trim', explode('|', $string)));
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Handle CSV import
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:102400',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();


        // Open CSV file
        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->with('error', 'Cannot read the CSV file.');
        }

        // Get headers
        $headers = fgetcsv($handle, 1000, ',');
        if (!$headers) {
            fclose($handle);
            return back()->with('error', 'Empty CSV file or invalid format.');
        }

        // Expected headers mapping
        $expectedHeaders = [
            'bookId',
            'title',
            'series',
            'author',
            'rating',
            'description',
            'language',
            'isbn',
            'genres',
            'characters',
            'bookFormat',
            'edition',
            'pages',
            'publisher',
            'publishDate',
            'firstPublishDate',
            'awards',
            'numRatings',
            'ratingsByStars',
            'likedPercent',
            'setting',
            'coverImg',
            'bbeScore',
            'bbeVotes',
            'price'
        ];

        // Validate headers (allow extra columns, but required ones must exist)
        $requiredHeaders = ['title', 'author', 'price'];
        $missingRequired = array_diff($requiredHeaders, $headers);
        if (!empty($missingRequired)) {
            fclose($handle);
            return back()->with('error', 'Missing required columns: ' . implode(', ', $missingRequired));
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
                if (empty($data['title']) || empty($data['author']) || empty($data['price'])) {
                    $skipped++;
                    $skippedRows[] = $rowNumber;
                    continue;
                }

                // Check if book already exists by ISBN or title+author
                $existingBook = null;
                if (!empty($data['isbn'])) {
                    $existingBook = Book::where('isbn', $data['isbn'])->first();
                }

                if (!$existingBook) {
                    $existingBook = Book::where('title', $data['title'])
                        ->where('author', $data['author'])
                        ->first();
                }

                if ($existingBook) {
                    $skipped++;
                    $skippedRows[] = $rowNumber;
                    continue;
                }

                // Parse genres from JSON array format
                $genreNames = $this->parseJsonArray($data['genres'] ?? '');
                $genreIds = [];
                foreach ($genreNames as $genreName) {
                    if (!empty(trim($genreName))) {
                        try {
                            $genre = Genre::firstOrCreate(['name' => trim($genreName), 'slug' => Str::slug(trim($genreName))]);
                            $genreIds[] = $genre->id;
                        } catch (\Exception $e) {
                            // Skip invalid genre
                            continue;
                        }
                    }
                }

                // Parse characters from JSON array format
                $characterNames = $this->parseJsonArray($data['characters'] ?? '');
                $characterIds = [];
                foreach ($characterNames as $characterName) {
                    if (!empty(trim($characterName))) {
                        try {
                            $character = Character::firstOrCreate(['name' => trim($characterName)]);
                            $characterIds[] = $character->id;
                        } catch (\Exception $e) {
                            // Skip invalid character
                            continue;
                        }
                    }
                }

                // Validate price
                $price = floatval($data['price'] ?? 0);
                if ($price < 0) {
                    $skipped++;
                    $skippedRows[] = $rowNumber;
                    continue;
                }

                // Parse publish date
                $publishDate = null;
                if (!empty($data['publishDate'])) {
                    try {
                        $publishDate = date('Y-m-d', strtotime($data['publishDate']));
                        if ($publishDate === '1970-01-01' || !$publishDate) {
                            $publishDate = null;
                        }
                    } catch (\Exception $e) {
                        $publishDate = null;
                    }
                }

                // Handle cover image if URL provided (optional)
                $coverPath = null;
                if (!empty($data['coverImg']) && filter_var($data['coverImg'], FILTER_VALIDATE_URL)) {
                    // You can optionally download the image from URL
                    $coverPath = $this->downloadCoverImage($data['coverImg']);
                }

                // Create book
                try {
                    $book = Book::create([
                        'title' => trim($data['title']),
                        'series' => !empty($data['series']) ? trim($data['series']) : null,
                        'author' => trim($data['author']),
                        'rating' => floatval($data['rating'] ?? 0),
                        'description' => !empty($data['description']) ? trim($data['description']) : null,
                        'language' => !empty($data['language']) ? trim($data['language']) : null,
                        'isbn' => !empty($data['isbn']) ? trim($data['isbn']) : null,
                        'stock' => 0, // Default stock
                        'pages' => !empty($data['pages']) ? intval($data['pages']) : null,
                        'publisher' => !empty($data['publisher']) ? trim($data['publisher']) : null,
                        'publish_date' => $publishDate,
                        'ratings' => !empty($data['numRatings']) ? intval($data['numRatings']) : 0,
                        'cover_img' => $coverPath,
                        'price' => $price * 17128.20, // convert to rupiah
                    ]);

                    // Attach genres and characters
                    if (!empty($genreIds)) {
                        $book->genres()->attach($genreIds);
                    }

                    if (!empty($characterIds)) {
                        $book->characters()->attach($characterIds);
                    }

                    $imported++;
                } catch (\Exception $e) {
                    // Skip row if book creation fails
                    $skipped++;
                    $skippedRows[] = $rowNumber;
                    continue;
                }
            }

            DB::commit();
            fclose($handle);

            $message = "Successfully imported {$imported} books.";
            if ($skipped > 0) {
                $message .= " Skipped {$skipped} rows (Rows: " . implode(', ', array_slice($skippedRows, 0, 10));
                if (count($skippedRows) > 10) {
                    $message .= "... and " . (count($skippedRows) - 10) . " more";
                }
                $message .= ")";
            }

            if ($imported === 0 && $skipped > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'No books were imported. ' . $message,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return response()->json([
                'success' => false,
                'message' => 'Fail to import. ',
            ], 500);
        }
    }

    /**
     * Optional: Download cover image from URL
     */
    private function downloadCoverImage($url)
    {
        try {
            $contents = file_get_contents($url);
            $name = substr(md5($url), 0, 10) . '.jpg';
            $path = 'book-covers/' . $name;
            Storage::disk('public')->put($path, $contents);
            return $path;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Download sample CSV template
     */
    public function downloadTemplate()
    {
        $headers = [
            'bookId',
            'title',
            'series',
            'author',
            'rating',
            'description',
            'language',
            'isbn',
            'genres',
            'characters',
            'bookFormat',
            'edition',
            'pages',
            'publisher',
            'publishDate',
            'firstPublishDate',
            'awards',
            'numRatings',
            'ratingsByStars',
            'likedPercent',
            'setting',
            'coverImg',
            'bbeScore',
            'bbeVotes',
            'price'
        ];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            // Add sample row
            fputcsv($file, [
                '1',
                'Sample Book Title',
                'Sample Series',
                'Sample Author',
                '4.5',
                'This is a sample description',
                'English',
                '978-1234567890',
                'Fiction|Mystery',
                'Character 1|Character 2',
                'Paperback',
                '1st Edition',
                '350',
                'Sample Publisher',
                '2024-01-01',
                '2023-01-01',
                'Best Book Award',
                '1000',
                '{"5":500,"4":300,"3":100,"2":50,"1":50}',
                '85',
                'New York',
                'https://example.com/cover.jpg',
                '4.2',
                '500',
                '19.99'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="books_import_template.csv"',
        ]);
    }

    public function updateAllMoodTags(Request $request)
    {
        try {

            $response = Http::withHeader('X-API-Key', env('AI_SERVICE_KEY'))
                ->post(env('AI_SERVICE_URL') . '/emotion/tag-books', []);

            if ($response->successful()) {
                $processed = $response->json()['books_processed'];

                return response()->json([
                    'success' => true,
                    'message' => "Updated {$processed} books successfully",
                    'updated' => $processed
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Ai Service Error",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateSingleBookMoodTags(string $id)
    {
        try {

            $response = Http::withHeader('X-API-Key', env('AI_SERVICE_KEY'))
                ->post(env('AI_SERVICE_URL') . '/emotion/tag-books-single?book_id=' . $id);

            if ($response->successful()) {
                $processed = $response->json()['books_processed'];

                return response()->json([
                    'success' => true,
                    'message' => "Updated {$processed} books successfully",
                    'updated' => $processed
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Ai Service Error",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
