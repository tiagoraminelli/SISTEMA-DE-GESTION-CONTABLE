<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movimientos_contables', function (Blueprint $table) {
            $table->id('idMovimiento');

            // FK a AsientoContable
            $table->unsignedBigInteger('AsientoContable_id');
            $table->foreign('AsientoContable_id')
                  ->references('idAsiento')
                  ->on('asientos_contables')
                  ->onDelete('cascade');

            // FK a CuentaContable
            $table->unsignedBigInteger('CuentaContable_id');
            $table->foreign('CuentaContable_id')
                  ->references('idCuentaContable')
                  ->on('cuentas_contables')
                  ->onDelete('restrict');

            $table->decimal('debe', 15, 2)->default(0);
            $table->decimal('haber', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_contables');
    }
};
