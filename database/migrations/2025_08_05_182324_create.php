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
        Schema::create('app_config', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('MaxCine');
            $table->string('app_logo')->default('https://example.com/logo.png');
            $table->string('app_version')->default('1.0.0');
            $table->string('api_key')->default('maxcinedomina');
            $table->boolean('force_update')->default(false);
            $table->string('update_url')->nullable();
            $table->string('update_message')->nullable();
            $table->boolean('enable_custom_message')->default(false);
            $table->string('custom_message')->default('Hi, welcome')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_config');
    }
};
