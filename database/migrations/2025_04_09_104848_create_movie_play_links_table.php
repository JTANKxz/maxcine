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
        Schema::create('movie_play_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('quality')->nullable(); // Ex: 720p, 1080p
            $table->integer('order')->default(0); // Ordem de exibição
            $table->string('url'); // URL do player
            $table->string('type'); // mp4, doodstream, etc.
            $table->enum('player_sub', ['free', 'premium'])->default('free'); // Tipo de player
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_play_links');
    }
};
