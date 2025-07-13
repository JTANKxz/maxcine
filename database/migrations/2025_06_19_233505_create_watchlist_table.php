<?php

// database/migrations/xxxx_xx_xx_create_watchlist_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchlistTable extends Migration
{
    public function up()
    {
        Schema::create('watchlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('content_id');
            $table->string('content_type'); // 'App\Models\Movie' ou 'App\Models\Serie'
            $table->timestamps();

            $table->unique(['user_id', 'content_id', 'content_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('watchlist');
    }
};
