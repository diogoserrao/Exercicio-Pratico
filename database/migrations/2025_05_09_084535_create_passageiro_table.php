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
        Schema::create('passageiro', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('identificacao', 12); // Cartão de Cidadão
            $table->string('nif', 9)->unique();
            $table->string('email')->unique();
            $table->string('telefone', 9);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passageiro');
    }
};
