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
        Schema::create('user_metadata', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Clave foránea para relacionar con la tabla users
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('real_name')->nullable(); // Otros campos de información adicional
            $table->text('address')->nullable();
            $table->text('contact')->nullable();
            $table->text('bibliography')->nullable(); // Campo para la bibliografía
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_metadata');
    }
};
