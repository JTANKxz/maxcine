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
            $table->string('content_type')->default('both'); // options: movie, serie, both
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auto_embed_urls', function (Blueprint $table) {
           
        });
    }
};
