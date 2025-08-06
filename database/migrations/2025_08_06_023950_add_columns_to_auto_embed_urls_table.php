<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('auto_embed_urls', function (Blueprint $table) {
            $table->string('name')->default('AutoEmbed')->after('id');         // Nome do player
            $table->string('type')->default('embed')->after('url');            // Tipo (embed, m3u8, etc)
            $table->string('player_sub')->default('free')->after('type');      // free ou premium
            $table->string('quality')->nullable()->default('Auto')->after('player_sub'); // Qualidade
        });
    }

    public function down(): void
    {
        Schema::table('auto_embed_urls', function (Blueprint $table) {
            $table->dropColumn(['name', 'type', 'player_sub', 'quality']);
        });
    }
};
