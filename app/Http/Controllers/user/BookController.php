<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;
use League\Uri\Http;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function bestsellers()
    {
        $books = Book::orderBy('rating', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $books,
        ]);
    }

    public function init(Request $request)
    {
        $genres = Genre::withCount('books')->get();

        $languages = Book::select('language')
            ->whereNotNull('language')
            ->distinct()
            ->pluck('language');

        $priceRange = [
            'min' => Book::min('price'),
            'max' => Book::max('price')
        ];

        $ratingRange = [
            'min' => Book::min('rating'),
            'max' => Book::max('rating')
        ];

        $authors = Book::select('author')
            ->whereNotNull('author')
            ->distinct()
            ->limit(50)
            ->pluck('author');

        $publishers = Book::select('publisher')
            ->whereNotNull('publisher')
            ->distinct()
            ->limit(50)
            ->pluck('publisher');

        return response()->json([
            'success' => true,
            'data' => [
                'genres' => $genres,
                'languages' => $languages,
                'price_range' => $priceRange,
                'rating_range' => $ratingRange,
                'authors' => $authors,
                'publishers' => $publishers,
            ]
        ]);
    }

    public function index(Request $request)
    {
        $query = Book::with(['genres', 'characters']);

        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('author', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('series', 'like', "%{$keyword}%")
                    ->orWhere('publisher', 'like', "%{$keyword}%");
            });
        }

        if ($request->has('genres') && !empty($request->genres)) {
            $genreIds = is_array($request->genres) ? $request->genres : explode(',', $request->genres);
            $query->whereHas('genres', function ($q) use ($genreIds) {
                $q->whereIn('genres.id', $genreIds);
            });
        }

        if ($request->has('language') && !empty($request->language)) {
            $query->where('language', $request->language);
        }

        if ($request->has('min_rating') && !empty($request->min_rating)) {
            $query->where('rating', '>=', $request->min_rating);
        }

        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('sort_by')) {
            $sortDirection = $request->input('sort_direction', 'asc');
            switch ($request->sort_by) {
                case 'rating':
                case 'price':
                case 'title':
                case 'publish_date':
                    $query->orderBy($request->sort_by, $sortDirection);
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        }

        $perPage = $request->input('per_page', 10);
        $books = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $books,
            'filters' => $request->only(['keyword', 'genres', 'language', 'min_rating', 'min_price', 'max_price', 'sort_by', 'sort_direction'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::with(['genres', 'characters'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $book,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
