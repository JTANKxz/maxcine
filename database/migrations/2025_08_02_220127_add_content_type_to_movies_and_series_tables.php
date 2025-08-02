<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('movies', function ($table) {
            $table->string('content_type')->nullable()->after('rating');
        });

        Schema::table('series', function ($table) {
            $table->string('content_type')->nullable()->after('rating');
        });
    }

    public function down()
    {
        Schema::table('movies', function ($table) {
            $table->dropColumn('content_type');
        });

        Schema::table('series', function ($table) {
            $table->dropColumn('content_type');
        });
    }
};
