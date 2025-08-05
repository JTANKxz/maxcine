<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auto_embed_urls', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->boolean('active')->default(true); // Para ativar/desativar alguma
            $table->unsignedInteger('order')->default(0); // Ordem opcional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auto_embed_urls');
    }
};
