<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaContable extends Model
{
    protected $table = 'cuentas_contables';
    protected $primaryKey = 'idCuentaContable';
    public $timestamps = true;

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',        // Activo, Pasivo, Patrimonio Neto, Resultado Positivo, Resultado Negativo
        'subtipo',     // Corriente, No Corriente
        'rubro',       // Ej: Caja, Bancos, Clientes, Proveedores, etc.
        'nivel',       // Nivel jerárquico
        'descripcion',
        'estado',      // true = activo, false = inactivo
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    /**
     * Relación futura: una cuenta puede tener muchos movimientos contables.
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoContable::class, 'CuentaContable_id', 'idCuentaContable');
    }
}
