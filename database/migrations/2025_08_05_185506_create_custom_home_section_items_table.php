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
        Schema::create('custom_home_section_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('content_id'); // ID do movie ou série
            $table->string('content_type');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            // Foreign key para garantir que a seção exista
            $table->foreign('section_id')
                ->references('id')
                ->on('custom_home_sections')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_home_section_items');
    }
};
