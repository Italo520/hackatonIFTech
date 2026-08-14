<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alertas', function (Blueprint $table) {
            if (!Schema::hasColumn('alertas', 'contato_emergencia')) {
                $table->string('contato_emergencia')->nullable()->after('urgencia');
            }
            if (!Schema::hasColumn('alertas', 'responsavel')) {
                $table->string('responsavel')->nullable()->after('contato_emergencia');
            }
            if (!Schema::hasColumn('alertas', 'duracao_horas')) {
                $table->integer('duracao_horas')->nullable()->default(24)->after('responsavel');
            }
            if (!Schema::hasColumn('alertas', 'valido_ate')) {
                $table->dateTime('valido_ate')->nullable()->after('duracao_horas');
            }
            if (!Schema::hasColumn('alertas', 'status')) {
                $table->string('status')->default('ativo')->after('valido_ate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('alertas', function (Blueprint $table) {
            $table->dropColumn(['contato_emergencia', 'responsavel', 'duracao_horas', 'valido_ate', 'status']);
        });
    }
};
