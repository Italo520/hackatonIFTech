<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistant_logs', function (Blueprint $table) {
            $table->id();
            $table->text('pergunta');
            $table->text('resposta');
            $table->json('fontes')->nullable();
            $table->string('idioma')->default('pt-BR');
            $table->boolean('feedback_util')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistant_logs');
    }
};
