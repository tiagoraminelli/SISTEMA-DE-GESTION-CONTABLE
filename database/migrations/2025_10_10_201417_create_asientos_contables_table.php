<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asientos_contables', function (Blueprint $table) {
            $table->id('idAsiento');
            $table->unsignedBigInteger('Cliente_id')->nullable();
            $table->foreign('Cliente_id')
                ->references('idCliente')
                ->on('Cliente')
                ->onDelete('set null');
            $table->date('fecha');
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asientos_contables');
    }
};
