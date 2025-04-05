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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('novel_id');
            $table->foreign('novel_id')
                ->references('id')
                ->on('novels')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable(); // Puede ser nulo si el usuario no está registrado
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('ip_address'); // Para registrar la dirección IP del visitante
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
