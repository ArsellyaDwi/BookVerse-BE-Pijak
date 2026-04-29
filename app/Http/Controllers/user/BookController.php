<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

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

    public function index()
    {
        $books = Book::with(['genres', 'characters'])->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $books,
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
