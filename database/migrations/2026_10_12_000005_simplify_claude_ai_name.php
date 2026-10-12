<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Game;

return new class extends Migration
{
    public function up(): void
    {
        $game = Game::where('slug', 'claude-ai')->first();
        if ($game && $game->name === 'Claude AI API (Claude Code CLI)') {
            $game->update([
                'name' => 'Claude AI',
            ]);
        }
    }

    public function down(): void
    {
        $game = Game::where('slug', 'claude-ai')->first();
        if ($game && $game->name === 'Claude AI') {
            $game->update([
                'name' => 'Claude AI API (Claude Code CLI)',
            ]);
        }
    }
};
