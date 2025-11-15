<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Cliente', function (Blueprint $table) {
            $table->id('idCliente');

            // Datos fiscales
            $table->string('CUIT', 11)->unique()->nullable()
                  ->comment('CUIT o CUIL del cliente según AFIP');
            $table->enum('TipoDocumento', ['CUIT', 'CUIL', 'DNI', 'Pasaporte', 'Otro'])->nullable();
            $table->string('NroDocumento', 15)->nullable();

            // Condiciones fiscales (sin foráneas para PyME)
            $table->string('CondicionIVA', 50)->nullable()
                  ->comment('Ej: Responsable Inscripto, Monotributista, Consumidor Final');
            $table->string('NroIngBrutos', 20)->nullable();
            $table->string('CondicionIngBrutos', 50)->nullable()
                  ->comment('Ej: Local, Multilateral');
            $table->enum('ResponsabilidadSocial', ['Física', 'Jurídica'])->default('Física');

            // Datos comerciales
            $table->string('RazonSocial', 150);
            $table->decimal('LimiteCredito', 15, 2)->default(0);
            $table->boolean('Activo')->default(true);

            // Contacto
            $table->string('DomicilioFiscal', 255)->nullable();
            $table->string('Localidad', 100)->nullable();
            $table->string('Provincia', 100)->nullable();
            $table->string('CodigoPostal', 10)->nullable();
            $table->string('Pais', 100)->default('Argentina');
            $table->string('Email', 100)->nullable();
            $table->string('Telefono', 50)->nullable();

            // Extras
            $table->text('Observaciones')->nullable();

            // Auditoría
            $table->date('FechaAlta')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Cliente');
    }
};
