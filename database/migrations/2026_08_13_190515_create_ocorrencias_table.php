<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ocorrencias', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->nullableMorphs('entidade');
            $table->string('local_texto')->nullable();
            $table->string('local')->nullable();
            $table->json('geo')->nullable();
            $table->string('gravidade')->default('baixa'); // baixa, media, alta
            $table->text('descricao');
            $table->string('status_atendimento')->default('aberto');
            $table->string('origem')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ocorrencias');
    }
};
