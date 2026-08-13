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
            Schema::create('municipios', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->char('uf', 2);
                $table->string('bbox_geo')->nullable();
                $table->string('tema_visual')->nullable();
                $table->json('config')->nullable();
                $table->timestamps();
            });
            return;
        }
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->char('uf', 2);
            $table->geometry('bbox_geo', 'POLYGON', 4326)->nullable();
            $table->string('tema_visual')->nullable();
            $table->jsonb('config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
