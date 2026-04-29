<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

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
}