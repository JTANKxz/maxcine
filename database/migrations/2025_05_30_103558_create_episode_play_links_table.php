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
        Schema::create('episode_play_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained()->onDelete('cascade');
            $table->string('url');
            $table->string('name');
            $table->string('type'); // mp4, m3u8, doodstream, embed, etc.
            $table->string('quality')->nullable(); // 720p, 1080p
            $table->integer('order')->default(0);
            $table->string('player_sub')->nullable(); // free, premium
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episode_play_links');
    }
};
