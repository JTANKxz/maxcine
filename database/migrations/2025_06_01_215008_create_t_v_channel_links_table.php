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
        Schema::create('tv_channel_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tv_channel_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('url');
            $table->string('type'); // ex: embed, m3u8, mp4
            $table->string('player_sub'); // free ou premium
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tv_channel_links');
    }
};
