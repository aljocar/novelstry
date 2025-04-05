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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Columna para el slug
            $table->mediumtext('content');
            $table->unsignedBigInteger('novel_id'); // Columna para la relación con la tabla novels
            $table->foreign('novel_id')
                  ->references('id')
                  ->on('novels')
                  ->onDelete('cascade'); // Si se elimina la novela, se eliminan sus capítulos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
