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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['movie', 'serie']); // Tipo de conteúdo
            $table->unsignedBigInteger('content_id'); // ID do conteúdo (filme ou série)
            $table->string('backdrop_url')->nullable();
            $table->string('title');
            $table->string('year')->nullable();
            $table->string('runtime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
