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
        Schema::create('midias', function (Blueprint $table) {
            $table->id();
            $table->morphs('entidade'); // Polymorphic relation (entidade_type, entidade_id)
            $table->enum('tipo', ['foto', 'video', '360', 'audio']);
            $table->string('url');
            $table->string('autor')->nullable();
            $table->string('licenca')->nullable();
            $table->string('alt_text'); // Mandatory accessibility PRD requirement
            $table->string('legenda')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('midias');
    }
};
