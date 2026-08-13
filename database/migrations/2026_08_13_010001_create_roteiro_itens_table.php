<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roteiro_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roteiro_id')->constrained()->cascadeOnDelete();
            $table->foreignId('atrativo_id')->constrained()->cascadeOnDelete();
            $table->integer('ordem');
            $table->integer('tempo_estimado')->nullable(); // in minutes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roteiro_itens');
    }
};
