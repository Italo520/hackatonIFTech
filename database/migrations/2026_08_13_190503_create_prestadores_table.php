<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tipo'); // hospedagem, gastronomia, guia
            $table->jsonb('dados');
            $table->jsonb('documentos')->nullable();
            $table->date('validade_documentos')->nullable();
            $table->string('status')->default('pendente'); // pendente, aprovado, rejeitado
            $table->boolean('selo_validado')->default(false);
            $table->timestamp('ultima_atualizacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestadores');
    }
};
