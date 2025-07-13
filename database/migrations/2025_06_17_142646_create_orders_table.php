<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('type'); // 'movie' ou 'serie'
            $table->unsignedBigInteger('tmdb_id'); // id do TMDB
            $table->string('title');
            $table->string('poster_url')->nullable();
            $table->year('year')->nullable();

            $table->string('status')->default('pending');
            $table->decimal('total', 10, 2)->default(0); // se precisar de valor
            $table->text('details')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
