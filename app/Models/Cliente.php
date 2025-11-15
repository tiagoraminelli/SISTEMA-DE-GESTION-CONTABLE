<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Importar el modelo del asiento
use App\Models\AsientoContable;

class Cliente extends Model
{
    use HasFactory; // Agregado para usar factories, buena práctica

    // Nombre de la tabla si no sigue la convención plural
    protected $table = 'Cliente';

    // Clave primaria si no es "id"
    protected $primaryKey = 'idCliente';

    // Si la PK no es auto-incremental o no es int
    // public $incrementing = true;
    // protected $keyType = 'int';

    // Para usar created_at y updated_at
    public $timestamps = true;

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'CUIT',
        'TipoDocumento',
        'NroDocumento',
        'CondicionIVA',
        'NroIngBrutos',
        'CondicionIngBrutos',
        'ResponsabilidadSocial',
        'RazonSocial',
        'LimiteCredito',
        'Activo',
        'DomicilioFiscal',
        'Localidad',
        'Provincia',
        'CodigoPostal',
        'Pais',
        'Email',
        'Telefono',
        'Observaciones',
        'FechaAlta',
    ];

    // Casts de columnas a tipos nativos
    protected $casts = [
        'LimiteCredito' => 'decimal:2',
        'Activo' => 'boolean',
        'FechaAlta' => 'date',
    ];

    /**
     * Relación uno a muchos: Un Cliente tiene muchos Asientos Contables.
     * Clave foránea en la tabla de AsientoContable: 'Cliente_id'
     * Clave local en la tabla Cliente: 'idCliente'
     */
    public function asientosContables()
    {
        // Esto resuelve el error "RelationNotFoundException"
        return $this->hasMany(AsientoContable::class, 'Cliente_id', 'idCliente');
    }
}
