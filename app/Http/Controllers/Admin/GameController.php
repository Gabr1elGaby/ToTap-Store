<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::withCount('products')->orderBy('name')->paginate(25);
        $vipBalance = (float) Setting::get('vip_balance_threshold', 0);
        return view('admin.games.index', compact('games', 'vipBalance'));
    }

    public function updateBalance(Request $request)
    {
        $request->validate([
            'balance' => 'required|numeric|min:0'
        ]);

        Setting::set('vip_balance_threshold', $request->balance);
        Setting::set('vip_reseller_balance', $request->balance);

        return back()->with('success', 'Batas saldo modal VIP Reseller berhasil diperbarui.');
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

        $uploadDir = public_path('images/games');
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_thumb_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['thumbnail'] = '/images/games/' . $fileName;
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $fileName = time() . '_cover_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['cover_image'] = '/images/games/' . $fileName;
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
            'thumbnail' => 'nullable|file|max:20480',
            'cover_image' => 'nullable|file|max:20480',
            'guide_text' => 'nullable|string',
            'target_field_1' => 'nullable|string',
            'target_field_2' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['requires_zone_id'] = $request->has('requires_zone_id');

        if ($request->name !== $game->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $destDirs = [
            base_path('images/games'),
            public_path('images/games'),
            base_path('public/images/games'),
        ];
        foreach ($destDirs as $d) {
            @mkdir($d, 0777, true);
        }

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $ext = $file->getClientOriginalExtension() ?: 'png';
            $fileName = time() . '_thumb_' . Str::random(6) . '.' . $ext;
            
            $saved = false;
            foreach ($destDirs as $d) {
                @copy($file->getRealPath(), $d . '/' . $fileName);
            }
            $file->move(base_path('images/games'), $fileName);
            $validated['thumbnail'] = '/images/games/' . $fileName;
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $ext = $file->getClientOriginalExtension() ?: 'png';
            $fileName = time() . '_cover_' . Str::random(6) . '.' . $ext;
            
            foreach ($destDirs as $d) {
                @copy($file->getRealPath(), $d . '/' . $fileName);
            }
            $file->move(base_path('images/games'), $fileName);
            $validated['cover_image'] = '/images/games/' . $fileName;
        }

        $game->update($validated);

        return redirect()->route('admin.games.edit', $game)->with('success', 'Game dan gambar berhasil disimpan!');
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('admin.games.index')->with('success', 'Game berhasil dihapus.');
    }
}
