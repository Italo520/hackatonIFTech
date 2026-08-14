<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::create('roteiros', function (Blueprint $table) {
                $table->id();
                $table->string('titulo');
                $table->string('tema')->nullable();
                $table->integer('duracao')->nullable();
                $table->string('dificuldade')->nullable();
                $table->string('transporte')->nullable();
                $table->decimal('orcamento', 10, 2)->nullable();
                $table->string('perfil')->nullable();
                $table->enum('origem', ['oficial', 'ia', 'usuario'])->default('oficial');
                $table->string('geo')->nullable();
                $table->decimal('distancia_total', 10, 2)->nullable();
                $table->boolean('publico')->default(true);
                $table->timestamps();
            });
            return;
        }

        Schema::create('roteiros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('tema')->nullable();
            $table->integer('duracao')->nullable();
            $table->string('dificuldade')->nullable();
            $table->string('transporte')->nullable();
            $table->decimal('orcamento', 10, 2)->nullable();
            $table->string('perfil')->nullable();
            $table->enum('origem', ['oficial', 'ia', 'usuario'])->default('oficial');
            $table->text('geo')->nullable();
            $table->decimal('distancia_total', 10, 2)->nullable();
            $table->boolean('publico')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roteiros');
    }
};
