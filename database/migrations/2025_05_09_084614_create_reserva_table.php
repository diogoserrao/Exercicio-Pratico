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
        Schema::create('reserva', function (Blueprint $table) {
            $table->id(); // id auto_increment e chave primária
            $table->string('numero_reserva');
            $table->unsignedInteger('preco'); // apenas número positivo, sem auto_increment
            $table->foreignId('voo_id')->constrained('voo');
            $table->foreignId('passageiro_id')->constrained('passageiro');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva');
    }
};
