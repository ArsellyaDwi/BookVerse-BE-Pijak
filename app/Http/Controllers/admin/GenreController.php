<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GenreController extends Controller
{
    /**
     * Display a listing of the genres.
     */
    public function index()
    {
        $genres = Genre::orderBy('name')->paginate(10);
        return view('pages.genres.index', compact('genres'));
    }

    /**
     * Show the form for creating a new genre.
     */
    public function create()
    {
        return view('pages.genres.create');
    }

    /**
     * Store a newly created genre in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'openness' => 'nullable|numeric|min:0|max:100',
            'conscientiousness' => 'nullable|numeric|min:0|max:100',
            'extroversion' => 'nullable|numeric|min:0|max:100',
            'agreeableness' => 'nullable|numeric|min:0|max:100',
            'neuroticism' => 'nullable|numeric|min:0|max:100',
        ]);

        // Set default traits if not provided
        $validated['openness'] = $validated['openness'] ?? 50;
        $validated['conscientiousness'] = $validated['conscientiousness'] ?? 50;
        $validated['extroversion'] = $validated['extroversion'] ?? 50;
        $validated['agreeableness'] = $validated['agreeableness'] ?? 50;
        $validated['neuroticism'] = $validated['neuroticism'] ?? 50;

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);
        
        // Check if slug is unique
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Genre::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('genre-images', 'public');
            $validated['image'] = $imagePath;
        }

        Genre::create($validated);

        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre created successfully!');
    }

    /**
     * Display the specified genre.
     */
    public function show(Genre $genre)
    {
        $genre->load('books');
        return view('pages.genres.show', compact('genre'));
    }

    /**
     * Show the form for editing the specified genre.
     */
    public function edit(Genre $genre)
    {
        return view('pages.genres.edit', compact('genre'));
    }

    /**
     * Update the specified genre in storage.
     */
    public function update(Request $request, Genre $genre)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name,' . $genre->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'openness' => 'nullable|numeric|min:0|max:100',
            'conscientiousness' => 'nullable|numeric|min:0|max:100',
            'extroversion' => 'nullable|numeric|min:0|max:100',
            'agreeableness' => 'nullable|numeric|min:0|max:100',
            'neuroticism' => 'nullable|numeric|min:0|max:100',
        ]);

        // Update slug if name changed
        if ($validated['name'] !== $genre->name) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Check if new slug is unique
            $originalSlug = $validated['slug'];
            $count = 1;
            while (Genre::where('slug', $validated['slug'])->where('id', '!=', $genre->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count;
                $count++;
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($genre->image && Storage::disk('public')->exists($genre->image)) {
                Storage::disk('public')->delete($genre->image);
            }
            
            $imagePath = $request->file('image')->store('genre-images', 'public');
            $validated['image'] = $imagePath;
        }

        $genre->update($validated);

        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre updated successfully!');
    }

    /**
     * Remove the specified genre from storage.
     */
    public function destroy(Genre $genre)
    {
        // Check if genre has books
        if ($genre->books()->count() > 0) {
            return redirect()->route('admin.genres.index')
                ->with('error', 'Cannot delete genre because it is associated with books.');
        }

        // Delete image if exists
        if ($genre->image && Storage::disk('public')->exists($genre->image)) {
            Storage::disk('public')->delete($genre->image);
        }

        $genre->delete();

        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre deleted successfully!');
    }

    // ============ API METHODS FOR FRONTEND ============

    /**
     * API: Get all genres (for frontend)
     * GET /api/genres
     */
    public function apiIndex()
    {
        $genres = Genre::select('id', 'name', 'slug', 'image', 
            'openness', 'conscientiousness', 'extroversion', 'agreeableness', 'neuroticism')
            ->orderBy('name')
            ->get();
        
        // Add full image URL
        foreach ($genres as $genre) {
            if ($genre->image) {
                $genre->image_url = Storage::url($genre->image);
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => $genres
        ]);
    }

    /**
     * API: Get single genre with its books
     * GET /api/genres/{id}
     */
    public function apiShow($id)
    {
        $genre = Genre::with('books')->findOrFail($id);
        
        // Add image URL
        $imageUrl = $genre->image ? Storage::url($genre->image) : null;
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $genre->id,
                'name' => $genre->name,
                'slug' => $genre->slug,
                'image' => $imageUrl,
                'traits' => [
                    'openness' => $genre->openness,
                    'conscientiousness' => $genre->conscientiousness,
                    'extroversion' => $genre->extroversion,
                    'agreeableness' => $genre->agreeableness,
                    'neuroticism' => $genre->neuroticism,
                ],
                'books' => $genre->books->map(function($book) {
                    return [
                        'id' => $book->id,
                        'title' => $book->title,
                        'author' => $book->author,
                        'cover_img' => $book->cover_img,
                        'price' => $book->price,
                        'rating' => $book->rating,
                    ];
                })
            ]
        ]);
    }

    /**
     * API: Get all books with their genres (for frontend)
     * GET /api/books-with-genres
     */
    public function apiBooksWithGenres()
    {
        $books = DB::table('books')
            ->leftJoin('book_genre', 'books.id', '=', 'book_genre.book_id')
            ->leftJoin('genres', 'book_genre.genre_id', '=', 'genres.id')
            ->select(
                'books.id',
                'books.title',
                'books.author',
                'books.cover_img',
                'books.price',
                'books.rating',
                DB::raw('GROUP_CONCAT(DISTINCT genres.name) as genres'),
                DB::raw('GROUP_CONCAT(DISTINCT genres.id) as genre_ids'),
                DB::raw('GROUP_CONCAT(DISTINCT genres.slug) as genre_slugs')
            )
            ->groupBy('books.id', 'books.title', 'books.author', 'books.cover_img', 'books.price', 'books.rating')
            ->get();
        
        foreach ($books as $book) {
            $book->genres = $book->genres ? explode(',', $book->genres) : [];
            $book->genre_ids = $book->genre_ids ? explode(',', $book->genre_ids) : [];
            $book->genre_slugs = $book->genre_slugs ? explode(',', $book->genre_slugs) : [];
        }
        
        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    /**
     * API: Get genres that have books (for filter)
     * GET /api/genres/active
     */
    public function apiActiveGenres()
    {
        $genres = DB::table('genres')
            ->join('book_genre', 'genres.id', '=', 'book_genre.genre_id')
            ->select('genres.id', 'genres.name', 'genres.slug')
            ->distinct()
            ->orderBy('genres.name')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $genres
        ]);
    }

    /**
     * API: Get genre by slug
     * GET /api/genres/slug/{slug}
     */
    public function apiBySlug($slug)
    {
        $genre = Genre::where('slug', $slug)->firstOrFail();
        
        return response()->json([
            'success' => true,
            'data' => $genre
        ]);
    }

    /**
     * API: Update genre traits (for personality recommendation)
     * PUT /api/genres/{id}/traits
     */
    public function apiUpdateTraits(Request $request, $id)
    {
        $validated = $request->validate([
            'openness' => 'required|numeric|min:0|max:100',
            'conscientiousness' => 'required|numeric|min:0|max:100',
            'extroversion' => 'required|numeric|min:0|max:100',
            'agreeableness' => 'required|numeric|min:0|max:100',
            'neuroticism' => 'required|numeric|min:0|max:100',
        ]);
        
        $genre = Genre::findOrFail($id);
        $genre->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Genre traits updated successfully',
            'data' => $genre
        ]);
    }

    /**
     * API: Sync book genres (for admin)
     * POST /api/admin/books/sync-genres
     */
    public function apiSyncBookGenres(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'genre_ids' => 'required|array',
            'genre_ids.*' => 'exists:genres,id'
        ]);
        
        $book = Book::find($validated['book_id']);
        $book->genres()->sync($validated['genre_ids']);
        
        return response()->json([
            'success' => true,
            'message' => 'Book genres updated successfully',
            'data' => [
                'book_id' => $book->id,
                'genre_ids' => $validated['genre_ids']
            ]
        ]);
    }

    /**
     * API: Bulk update genre traits
     * POST /api/genres/bulk-update-traits
     */
    public function apiBulkUpdateTraits(Request $request)
    {
        $validated = $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|exists:genres,id',
            'updates.*.openness' => 'nullable|numeric|min:0|max:100',
            'updates.*.conscientiousness' => 'nullable|numeric|min:0|max:100',
            'updates.*.extroversion' => 'nullable|numeric|min:0|max:100',
            'updates.*.agreeableness' => 'nullable|numeric|min:0|max:100',
            'updates.*.neuroticism' => 'nullable|numeric|min:0|max:100',
        ]);
        
        $updated = [];
        foreach ($validated['updates'] as $update) {
            $genre = Genre::find($update['id']);
            $genre->update(array_filter($update, fn($key) => in_array($key, [
                'openness', 'conscientiousness', 'extroversion', 'agreeableness', 'neuroticism'
            ]), ARRAY_FILTER_USE_KEY));
            $updated[] = $genre;
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Genres traits updated successfully',
            'data' => $updated
        ]);
    }
}