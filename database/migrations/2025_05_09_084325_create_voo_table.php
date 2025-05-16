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
        Schema::create('voo', function (Blueprint $table) {
            $table->id();
            $table->string('numero_voo')->unique();
            $table->date('data');
            $table->unsignedBigInteger('origem_id');
            $table->unsignedBigInteger('destino_id');

            $table->foreign('origem_id')->references('id')->on('cidades')->onDelete('restrict');
            $table->foreign('destino_id')->references('id')->on('cidades')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voo');
    }
};
