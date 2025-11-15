<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('cuentas_contables', function (Blueprint $table) {
        $table->id('idCuentaContable');
        $table->string('codigo', 50)->unique();
        $table->string('nombre', 255);
        $table->enum('tipo', ['Activo', 'Pasivo', 'Patrimonio Neto', 'Resultado Positivo', 'Resultado Negativo']);
        $table->integer('nivel')->default(3);
        $table->text('descripcion')->nullable();
        $table->boolean('estado')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_contables');
    }
};
