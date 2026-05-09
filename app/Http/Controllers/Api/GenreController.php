<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Book;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Get all genres (for frontend)
     * GET /api/genre
     */
    public function index()
    {
        $genres = Genre::select('id', 'name', 'slug', 'image')
            ->orderBy('name')
            ->get();

        // Add book count for each genre
        foreach ($genres as $genre) {
            $genre->book_count = $genre->books()->count();
        }

        return response()->json([
            'success' => true,
            'data' => $genres
        ]);
    }

    /**
     * Get single genre detail
     * GET /api/genre/{slug}
     */
    public function show($slug)
    {
        $genre = Genre::where('slug', $slug)
            ->select('id', 'name', 'slug', 'image')
            ->first();

        if (!$genre) {
            return response()->json([
                'success' => false,
                'message' => 'Genre not found'
            ], 404);
        }

        $genre->book_count = $genre->books()->count();

        return response()->json([
            'success' => true,
            'data' => $genre
        ]);
    }

    /**
     * Get books by genre slug
     * GET /api/genres/{slug}/books
     */
    public function getBooksByGenre($slug, Request $request)
    {
        // Find genre by slug
        $genre = Genre::where('slug', $slug)->first();

        if (!$genre) {
            return response()->json([
                'success' => false,
                'message' => 'Genre not found'
            ], 404);
        }

        // Query books related to this genre
        $query = $genre->books();

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        switch ($request->get('sort', 'latest')) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $limit = $request->get('limit', 12);
        $books = $query->paginate($limit);

        // Format response
        $booksData = $books->items();
        
        // Add rating if not exists
        foreach ($booksData as $book) {
            $book->rating = $book->rating ?? rand(35, 50) / 10;
        }

        return response()->json([
            'success' => true,
            'data' => $booksData,
            'total' => $books->total(),
            'current_page' => $books->currentPage(),
            'last_page' => $books->lastPage(),
            'per_page' => $books->perPage(),
            'genre' => [
                'id' => $genre->id,
                'name' => $genre->name,
                'slug' => $genre->slug,
                'image' => $genre->image,
            ]
        ]);
    }
}