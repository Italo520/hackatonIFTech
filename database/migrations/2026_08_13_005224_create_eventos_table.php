<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::create('eventos', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->text('descricao');
                $table->string('local')->nullable();
                $table->string('geo')->nullable(); // fallback for sqlite testing
                $table->dateTime('inicio');
                $table->dateTime('fim')->nullable();
                $table->string('organizador')->nullable();
                $table->string('ingressos')->nullable();
                $table->integer('capacidade')->nullable();
                $table->string('faixa_etaria')->nullable();
                $table->boolean('gratuito')->default(false);
                $table->json('acessibilidade')->nullable();
                $table->enum('status', ['ativo', 'alterado', 'cancelado'])->default('ativo');
                $table->timestamps();
            });
            return;
        }

        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao');
            $table->string('local')->nullable();
            $table->text('geo')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->dateTime('inicio');
            $table->dateTime('fim')->nullable();
            $table->string('organizador')->nullable();
            $table->string('ingressos')->nullable();
            $table->integer('capacidade')->nullable();
            $table->string('faixa_etaria')->nullable();
            $table->boolean('gratuito')->default(false);
            $table->jsonb('acessibilidade')->nullable();
            $table->enum('status', ['ativo', 'alterado', 'cancelado'])->default('ativo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
