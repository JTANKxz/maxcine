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
            $table->enum('id_type', ['tmdb', 'imdb'])->default('tmdb')->after('url');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auto_embed_urls', function (Blueprint $table) {
            //
        });
    }
};
