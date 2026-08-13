<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qrcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atrativo_id')->constrained()->cascadeOnDelete();
            $table->string('hash_code')->unique();
            $table->integer('impressoes')->default(0);
            $table->integer('scans')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qrcodes');
    }
};
