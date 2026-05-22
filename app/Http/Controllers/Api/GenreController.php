<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::withCount('books')->get();

        // Track used book IDs to avoid duplication
        $usedBookIds = [];

        $genres->each(function ($genre) use (&$usedBookIds) {
            $bestSeller = $genre->books()
                ->orderBy('rating', 'desc')
                ->whereNotIn('books.id', $usedBookIds)
                ->first();

            if ($bestSeller && $bestSeller->cover_img) {
                $genre->image = asset('storage/' . $bestSeller->cover_img);
                $usedBookIds[] = $bestSeller->id;
            } else {
                $anyBook = $genre->books()->first();
                $genre->image = $anyBook && $anyBook->cover_img
                    ? asset('storage/' . $anyBook->cover_img)
                    : $genre->image;
            }
        });

        return response()->json([
            'success' => true,
            'data' => $genres
        ]);
    }

    public function show($slug)
    {
        $genre = Genre::where('slug', $slug)
            ->withCount('books')
            ->firstOrFail();

        $bestSeller = $genre->books()
            ->orderBy('rating', 'desc')
            ->first();

        $genre->image = $bestSeller && $bestSeller->cover_img
            ? asset('storage/' . $bestSeller->cover_img)
            : $genre->image;

        return response()->json([
            'success' => true,
            'data' => $genre
        ]);
    }

    public function getBooksByGenre($slug)
    {
        $genre = Genre::with(['books'])->where('slug', $slug)->firstOrFail();
        return response()->json([
            'success' => true,
            'data' => $genre->books,
            'total' => $genre->books->count(),
            // 'current_page' => $books->currentPage(),
            // 'last_page' => $books->lastPage(),
            // 'per_page' => $books->perPage(),
            'genre' => $genre
        ]);
    }

    public function getGenresWithUniqueImages()
    {
        $genres = Genre::withCount('books')->get();

        $allBooks = collect();
        foreach ($genres as $genre) {
            $books = $genre->books()
                ->select('books.id', 'books.title', 'books.rating', 'books.cover_img')
                ->get()
                ->map(function ($book) use ($genre) {
                    return [
                        'book_id' => $book->id,
                        'genre_id' => $genre->id,
                        'genre_slug' => $genre->slug,
                        'rating' => $book->rating,
                        'cover_img' => $book->cover_img,
                    ];
                });
            $allBooks = $allBooks->concat($books);
        }

        $sortedBooks = $allBooks->sortByDesc('rating');

        $usedBookIds = [];
        $result = [];

        foreach ($genres as $genre) {
            $bestBookForGenre = $sortedBooks
                ->where('genre_id', $genre->id)
                ->whereNotIn('book_id', $usedBookIds)
                ->first();

            if ($bestBookForGenre && $bestBookForGenre['cover_img']) {
                $image = asset('storage/' . $bestBookForGenre['cover_img']);
                $usedBookIds[] = $bestBookForGenre['book_id'];
            } else {

                $anyBook = $genre->books()->first();
                $image = $anyBook && $anyBook->cover_img
                    ? asset('storage/' . $anyBook->cover_img)
                    : null;
            }

            $result[] = [
                'id' => $genre->id,
                'name' => $genre->name,
                'slug' => $genre->slug,
                'book_count' => $genre->books_count,
                'image' => $image,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    /**
     * Get all genres with personality traits (for recommendation system)
     * GET /api/genres/with-traits
     */
    public function getGenresWithTraits()
    {
        $genres = Genre::select(
            'id', 
            'name', 
            'slug',
            'openness',
            'conscientiousness',
            'extroversion',
            'agreeableness',
            'neuroticism'
        )->orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'data' => $genres
        ]);
    }

    /**
     * Get active genres (genres that have books)
     * GET /api/genres/active
     */
    public function getActiveGenres()
    {
        $genres = DB::table('genres')
            ->join('book_genre', 'genres.id', '=', 'book_genre.genre_id')
            ->select('genres.id', 'genres.name', 'genres.slug')
            ->distinct()
            ->orderBy('genres.name')
            ->get();
        
        // Add image for each genre
        foreach ($genres as $genre) {
            $anyBook = DB::table('book_genre')
                ->join('books', 'book_genre.book_id', '=', 'books.id')
                ->where('book_genre.genre_id', $genre->id)
                ->select('books.cover_img')
                ->first();
            
            $genre->image = $anyBook && $anyBook->cover_img 
                ? asset('storage/' . $anyBook->cover_img) 
                : null;
        }
        
        return response()->json([
            'success' => true,
            'data' => $genres
        ]);
    }

    /**
     * Get single genre with its personality traits
     * GET /api/genres/{id}/traits
     */
    public function getGenreTraits($id)
    {
        $genre = Genre::select(
            'id', 
            'name', 
            'slug',
            'openness',
            'conscientiousness',
            'extroversion',
            'agreeableness',
            'neuroticism'
        )->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $genre
        ]);
    }

    /**
     * Update genre personality traits (for admin)
     * PUT /api/genres/{id}/traits
     */
    public function updateGenreTraits(Request $request, $id)
    {
        $validated = $request->validate([
            'openness' => 'nullable|numeric|min:0|max:100',
            'conscientiousness' => 'nullable|numeric|min:0|max:100',
            'extroversion' => 'nullable|numeric|min:0|max:100',
            'agreeableness' => 'nullable|numeric|min:0|max:100',
            'neuroticism' => 'nullable|numeric|min:0|max:100',
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
     * Get all books with their genres (for frontend)
     * GET /api/books-with-genres
     */
    public function getBooksWithGenres()
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
                'books.description',
                DB::raw('GROUP_CONCAT(DISTINCT genres.name) as genres'),
                DB::raw('GROUP_CONCAT(DISTINCT genres.id) as genre_ids'),
                DB::raw('GROUP_CONCAT(DISTINCT genres.slug) as genre_slugs')
            )
            ->groupBy(
                'books.id', 
                'books.title', 
                'books.author', 
                'books.cover_img', 
                'books.price', 
                'books.rating',
                'books.description'
            )
            ->paginate(20);
        
        foreach ($books as $book) {
            $book->genres = $book->genres ? explode(',', $book->genres) : [];
            $book->genre_ids = $book->genre_ids ? explode(',', $book->genre_ids) : [];
            $book->genre_slugs = $book->genre_slugs ? explode(',', $book->genre_slugs) : [];
            
            if ($book->cover_img) {
                $book->cover_url = asset('storage/' . $book->cover_img);
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    /**
     * Get books by genre ID with pagination
     * GET /api/genres/{id}/books
     */
    public function getBooksByGenreId($id)
    {
        $genre = Genre::findOrFail($id);
        
        $books = $genre->books()
            ->select('books.id', 'books.title', 'books.author', 'books.cover_img', 'books.price', 'books.rating')
            ->paginate(12);
        
        foreach ($books as $book) {
            if ($book->cover_img) {
                $book->cover_url = asset('storage/' . $book->cover_img);
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'genre' => [
                    'id' => $genre->id,
                    'name' => $genre->name,
                    'slug' => $genre->slug,
                    'traits' => [
                        'openness' => $genre->openness,
                        'conscientiousness' => $genre->conscientiousness,
                        'extroversion' => $genre->extroversion,
                        'agreeableness' => $genre->agreeableness,
                        'neuroticism' => $genre->neuroticism,
                    ]
                ],
                'books' => $books->items(),
                'pagination' => [
                    'total' => $books->total(),
                    'current_page' => $books->currentPage(),
                    'last_page' => $books->lastPage(),
                    'per_page' => $books->perPage(),
                ]
            ]
        ]);
    }
}