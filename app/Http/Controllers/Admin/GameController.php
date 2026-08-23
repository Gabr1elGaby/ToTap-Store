<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::withCount('products')->latest()->paginate(15);
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'developer' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif,webp,svg,avif|max:5120',
            'cover_image' => 'nullable|mimes:jpeg,png,jpg,gif,webp,svg,avif|max:5120',
            'guide_text' => 'nullable|string',
            'target_field_1' => 'nullable|string',
            'target_field_2' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['requires_zone_id'] = $request->has('requires_zone_id');
        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('games', 'public');
            $validated['thumbnail'] = Storage::url($path);
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('games', 'public');
            $validated['cover_image'] = Storage::url($path);
        }
        
        Game::create($validated);

        return redirect()->route('admin.games.index')->with('success', 'Game berhasil ditambahkan.');
    }

    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'developer' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif,webp,svg,avif|max:5120',
            'cover_image' => 'nullable|mimes:jpeg,png,jpg,gif,webp,svg,avif|max:5120',
            'guide_text' => 'nullable|string',
            'target_field_1' => 'nullable|string',
            'target_field_2' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['requires_zone_id'] = $request->has('requires_zone_id');

        if ($request->name !== $game->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('games', 'public');
            $validated['thumbnail'] = Storage::url($path);
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('games', 'public');
            $validated['cover_image'] = Storage::url($path);
        }

        $game->update($validated);

        return redirect()->route('admin.games.index')->with('success', 'Game berhasil diperbarui.');
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('admin.games.index')->with('success', 'Game berhasil dihapus.');
    }
}
