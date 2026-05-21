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
}
