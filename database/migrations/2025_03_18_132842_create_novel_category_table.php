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
        Schema::create('category_novel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('novel_id');
            $table->unsignedBigInteger('category_id');
            $table->foreign('novel_id')
                    ->references('id')
                    ->on('novels')
                    ->onDelete('cascade');
            $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novel_category');
    }
};
