<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

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
        ]);

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
        ]);

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

        $genre->delete();

        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre deleted successfully!');
    }
}
