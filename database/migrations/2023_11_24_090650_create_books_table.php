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
        Schema::create('books', function (Blueprint $table) {
            // Request body should include book details such as title, author, publication year, and ISBN.
            $table->id();
            $table->string('isbn', 13)->unique();
            $table->string('title');
            $table->foreignId('author_id');
            $table->string('publication_year', 4);
            $table->timestamps();

            $table->foreign('author_id')->on('authors')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
