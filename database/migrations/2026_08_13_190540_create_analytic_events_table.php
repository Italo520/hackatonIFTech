<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytic_events', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->json('geo')->nullable();
            $table->json('metadados')->nullable();
            $table->nullableMorphs('entidade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytic_events');
    }
};
