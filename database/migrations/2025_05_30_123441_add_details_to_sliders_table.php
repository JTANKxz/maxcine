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
        Schema::table('sliders', function (Blueprint $table) {
            //$table->string('slug')->nullable();
            //$table->float('rating')->nullable();
            //$table->integer('runtime')->nullable(); // só usado em filmes
            $table->integer('seasons_count')->nullable(); // só usado em séries
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['slug', 'rating', 'runtime', 'seasons_count']);
        });
    }
};
