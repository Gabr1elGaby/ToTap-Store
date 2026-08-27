<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $categoryFilter = $request->query('category', 'all');
        $query = Game::withCount('products')->orderBy('name');

        if ($categoryFilter === 'app-premium') {
            $query->where(function($q) {
                $q->where('category', 'Aplikasi Premium')
                  ->orWhere('category', 'App & Entertainment')
                  ->orWhere('category', 'LIKE', '%aplikasi%')
                  ->orWhere('category', 'LIKE', '%streaming%');
            });
        } elseif ($categoryFilter === 'game') {
            $query->where(function($q) {
                $q->whereNull('category')
                  ->orWhere('category', '')
                  ->orWhere('category', 'Mobile Game')
                  ->orWhere('category', 'PC Game')
                  ->orWhere('category', 'Voucher')
                  ->orWhere(function($sq) {
                      $sq->where('category', 'NOT LIKE', '%aplikasi%')
                         ->where('category', 'NOT LIKE', '%streaming%')
                         ->where('category', 'NOT LIKE', '%entertainment%');
                  });
            });
        }

        $games = $query->paginate(25)->appends($request->query());

        // Count totals for tab badges
        $totalAll = Game::count();
        $totalApp = Game::where(function($q) {
            $q->where('category', 'Aplikasi Premium')
              ->orWhere('category', 'App & Entertainment')
              ->orWhere('category', 'LIKE', '%aplikasi%')
              ->orWhere('category', 'LIKE', '%streaming%');
        })->count();
        $totalGame = Game::where(function($q) {
            $q->whereNull('category')
              ->orWhere('category', '')
              ->orWhere('category', 'Mobile Game')
              ->orWhere('category', 'PC Game')
              ->orWhere('category', 'Voucher')
              ->orWhere(function($sq) {
                  $sq->where('category', 'NOT LIKE', '%aplikasi%')
                     ->where('category', 'NOT LIKE', '%streaming%')
                     ->where('category', 'NOT LIKE', '%entertainment%');
              });
        })->count();

        $vipBalance = (float) Setting::get('vip_balance_threshold', 0);
        $vipProfileData = null;

        // Auto-fetch real-time VIP Reseller Balance from API
        try {
            $vip = new \App\Services\VipResellerService();
            $profile = $vip->getProfile();
            if (isset($profile['result']) && $profile['result'] === true && isset($profile['data']['balance'])) {
                $liveBal = (float) $profile['data']['balance'];
                Setting::set('vip_balance_threshold', (string) $liveBal);
                Setting::set('vip_reseller_balance', (string) $liveBal);
                $vipBalance = $liveBal;
                $vipProfileData = $profile['data'];
            }
        } catch (\Throwable $e) {
            // Keep existing balance from setting on network error
        }

        return view('admin.games.index', compact('games', 'vipBalance', 'vipProfileData', 'categoryFilter', 'totalAll', 'totalGame', 'totalApp'));
    }

    public function syncBalance()
    {
        try {
            $vip = new \App\Services\VipResellerService();
            $profile = $vip->getProfile();
            if (isset($profile['result']) && $profile['result'] === true && isset($profile['data']['balance'])) {
                $liveBal = (float) $profile['data']['balance'];
                Setting::set('vip_balance_threshold', (string) $liveBal);
                Setting::set('vip_reseller_balance', (string) $liveBal);
                return back()->with('success', 'Saldo VIP Reseller berhasil disinkronkan langsung dari API: Rp ' . number_format($liveBal, 0, ',', '.'));
            }
            return back()->with('error', 'Gagal memuat saldo dari VIP Reseller: ' . ($profile['message'] ?? 'API tidak merespons'));
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateBalance(Request $request)
    {
        $request->validate([
            'balance' => 'required|numeric|min:0'
        ]);

        $val = (string) $request->balance;
        Setting::set('vip_balance_threshold', $val);
        Setting::set('vip_reseller_balance', $val);

        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }

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
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif,webp,svg,avif|max:5120',
            'cover_image' => 'nullable|mimes:jpeg,png,jpg,gif,webp,svg,avif|max:5120',
            'guide_text' => 'nullable|string',
            'target_field_1' => 'nullable|string',
            'target_field_2' => 'nullable|string',
            'target_field_1_help' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->input('is_active') == '1' || $request->input('is_active') === true || $request->has('is_active');
        $validated['requires_zone_id'] = $request->has('requires_zone_id') ? 1 : 0;
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

        // Ensure columns exist in database if migration hasn't run yet
        if (!\Illuminate\Support\Facades\Schema::hasColumn('games', 'target_field_1_help')) {
            try {
                \Illuminate\Support\Facades\Schema::table('games', function ($table) {
                    $table->text('target_field_1_help')->nullable();
                });
            } catch (\Throwable $e) {}
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('games', 'description')) {
            try {
                \Illuminate\Support\Facades\Schema::table('games', function ($table) {
                    $table->text('description')->nullable();
                });
            } catch (\Throwable $e) {}
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
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|file|max:20480',
            'cover_image' => 'nullable|file|max:20480',
            'guide_text' => 'nullable|string',
            'target_field_1' => 'nullable|string',
            'target_field_2' => 'nullable|string',
            'target_field_1_help' => 'nullable|string',
            'is_active' => 'nullable',
            'requires_zone_id' => 'nullable',
        ]);

        $validated['is_active'] = $request->input('is_active') == '1' || $request->input('is_active') === true;
        $validated['requires_zone_id'] = $request->has('requires_zone_id') ? 1 : 0;

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

        // Ensure columns exist in database if migration hasn't run yet
        if (!\Illuminate\Support\Facades\Schema::hasColumn('games', 'target_field_1_help')) {
            try {
                \Illuminate\Support\Facades\Schema::table('games', function ($table) {
                    $table->text('target_field_1_help')->nullable();
                });
            } catch (\Throwable $e) {}
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('games', 'description')) {
            try {
                \Illuminate\Support\Facades\Schema::table('games', function ($table) {
                    $table->text('description')->nullable();
                });
            } catch (\Throwable $e) {}
        }

        $game->update($validated);

        return redirect()->route('admin.games.edit', $game)->with('success', 'Game dan gambar berhasil disimpan!');
    }

    public function destroy(Game $game)
    {
        try {
            $gameName = $game->name;
            \Illuminate\Support\Facades\DB::transaction(function () use ($game) {
                $productIds = $game->products()->pluck('id')->toArray();
                if (!empty($productIds)) {
                    try {
                        \App\Models\Transaction::whereIn('game_product_id', $productIds)->update(['game_product_id' => null]);
                    } catch (\Throwable $e) {}
                    try {
                        \Illuminate\Support\Facades\DB::table('topup_transactions')->whereIn('game_product_id', $productIds)->update(['game_product_id' => null]);
                    } catch (\Throwable $e) {}
                }

                try {
                    \App\Models\Transaction::where('game_id', $game->id)->update(['game_id' => null]);
                } catch (\Throwable $e) {}
                try {
                    \Illuminate\Support\Facades\DB::table('topup_transactions')->where('game_id', $game->id)->update(['game_id' => null]);
                } catch (\Throwable $e) {}

                $game->products()->delete();
                $game->delete();
            });

            return redirect()->route('admin.games.index')->with('success', "Game '{$gameName}' beserta seluruh produknya berhasil dihapus.");
        } catch (\Throwable $e) {
            return redirect()->route('admin.games.index')->with('error', 'Gagal menghapus game: ' . $e->getMessage());
        }
    }
}
