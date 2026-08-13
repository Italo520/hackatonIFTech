<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::create('atrativos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('municipio_id')->constrained()->cascadeOnDelete();
                $table->foreignId('categoria_id')->constrained()->cascadeOnDelete();
                $table->string('nome');
                $table->text('descricao');
                $table->text('historia')->nullable();
                $table->string('endereco')->nullable();
                $table->string('geo')->nullable(); // fallback for sqlite testing
                $table->json('horarios')->nullable();
                $table->integer('tempo_medio_visita')->nullable();
                $table->json('precos')->nullable();
                $table->json('contatos')->nullable();
                $table->json('acessibilidade')->nullable();
                $table->text('restricoes')->nullable();
                $table->text('seguranca')->nullable();
                $table->string('status')->default('ativo');
                $table->foreignId('validado_por')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('validado_em')->nullable();
                $table->timestamps();
            });
            return;
        }

        Schema::create('atrativos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('municipio_id')->constrained('municipios')->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->string('nome');
            $table->text('descricao');
            $table->text('historia')->nullable();
            $table->string('endereco')->nullable();
            $table->geometry('geo', 'POINT', 4326)->nullable();
            $table->jsonb('horarios')->nullable();
            $table->integer('tempo_medio_visita')->nullable(); // em minutos
            $table->jsonb('precos')->nullable();
            $table->jsonb('contatos')->nullable();
            $table->jsonb('acessibilidade')->nullable();
            $table->text('restricoes')->nullable();
            $table->text('seguranca')->nullable();
            $table->string('status')->default('ativo');
            $table->foreignId('validado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validado_em')->nullable();
            $table->timestamps();
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE atrativos ADD COLUMN search_vector tsvector GENERATED ALWAYS AS (to_tsvector('portuguese', coalesce(nome, '') || ' ' || coalesce(descricao, ''))) STORED;");
            DB::statement("CREATE INDEX atrativos_search_vector_idx ON atrativos USING GIN(search_vector);");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atrativos');
    }
};
