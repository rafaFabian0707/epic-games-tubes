<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `games` (
 *   FULLTEXT KEY games_search_fulltext (title, main_desc, desc)
 * );
 */
public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {

            $table->fullText([
                'title',
                'main_desc',
                'desc'
            ], 'games_search_fulltext');
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {

            $table->dropFullText('games_search_fulltext');
        });
    }
};