<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->morphs('avaliable');
            $table->integer('nota');
            $table->text('comentario')->nullable();
            $table->string('sentimento')->nullable(); // positivo, negativo, neutro
            $table->string('status_moderacao')->default('pendente'); // pendente, aprovada, rejeitada
            $table->boolean('origem_offline')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};
