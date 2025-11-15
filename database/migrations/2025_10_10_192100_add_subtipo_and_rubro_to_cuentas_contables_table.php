<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuentas_contables', function (Blueprint $table) {
            $table->enum('subtipo', ['Corriente', 'No Corriente'])->nullable()->after('tipo');
            $table->string('rubro', 100)->nullable()->after('subtipo');
        });
    }

    public function down(): void
    {
        Schema::table('cuentas_contables', function (Blueprint $table) {
            $table->dropColumn('subtipo');
            $table->dropColumn('rubro');
        });
    }
};
