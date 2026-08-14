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
            $table->nullableMorphs('entidade');
            $table->string('geo')->nullable();
            $table->json('metadados')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytic_events');
    }
};
