<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;

class ParentGameSeeder extends Seeder
{
    public function run(): void
    {
        $games = Game::all();

        $keywords = [
            'Ultimate Edition',
            'Deluxe Edition',
            'Gold Edition',
            'Complete Edition',
            'Definitive Edition',
            'Demo',
            'Bundle',
            'DLC',
            'Add-On',
            'Expansion',
        ];

        foreach ($games as $game) {

            $originalTitle = $game->title;

            $baseTitle = $originalTitle;

            foreach ($keywords as $keyword) {

                $baseTitle = str_ireplace($keyword, '', $baseTitle);
            }

            $baseTitle = trim($baseTitle);

            // skip kalau sama persis
            if (strtolower($baseTitle) === strtolower($originalTitle)) {
                continue;
            }

            // cari parent
            $parent = Game::where('title', 'LIKE', $baseTitle . '%')
                ->where('game_id', '!=', $game->game_id)
                ->whereNull('parent_game_id')
                ->first();

            if ($parent) {

                $type = 'edition';

                $lower = strtolower($originalTitle);

                if (str_contains($lower, 'demo')) {
                    $type = 'demo';
                }
                elseif (
                    str_contains($lower, 'dlc') ||
                    str_contains($lower, 'add-on') ||
                    str_contains($lower, 'expansion')
                ) {
                    $type = 'addon';
                }
                elseif (str_contains($lower, 'bundle')) {
                    $type = 'bundle';
                }

                $game->update([
                    'parent_game_id' => $parent->game_id,
                    'game_type' => $type,
                ]);

                echo "Linked: {$game->title} -> {$parent->title}\n";
            }
        }
    }
}