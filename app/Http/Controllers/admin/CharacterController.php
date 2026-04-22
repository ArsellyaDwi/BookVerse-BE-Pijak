<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    /**
     * Display a listing of the characters.
     */
    public function index(Request $request)
    {
        $query = Character::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $characters = $query->orderBy('name')->paginate(10);
        $characters->appends(['search' => $request->search]);

        return view('pages.characters.index', compact('characters'));
    }

    /**
     * Show the form for creating a new character.
     */
    public function create()
    {
        return view('pages.characters.create');
    }

    /**
     * Store a newly created character in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:characters,name',
        ]);

        Character::create($validated);

        return redirect()->route('admin.characters.index')
            ->with('success', 'Character created successfully!');
    }

    /**
     * Display the specified character.
     */
    public function show(Character $character)
    {
        $character->load('books');
        return view('pages.characters.show', compact('character'));
    }

    /**
     * Show the form for editing the specified character.
     */
    public function edit(Character $character)
    {
        return view('pages.characters.edit', compact('character'));
    }

    /**
     * Update the specified character in storage.
     */
    public function update(Request $request, Character $character)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:characters,name,' . $character->id,
        ]);

        $character->update($validated);

        return redirect()->route('admin.characters.index')
            ->with('success', 'Character updated successfully!');
    }

    /**
     * Remove the specified character from storage.
     */
    public function destroy(Character $character)
    {
        // Check if character has books
        if ($character->books()->count() > 0) {
            return redirect()->route('admin.characters.index')
                ->with('error', 'Cannot delete character because it is associated with books.');
        }

        $character->delete();

        return redirect()->route('admin.characters.index')
            ->with('success', 'Character deleted successfully!');
    }
}
