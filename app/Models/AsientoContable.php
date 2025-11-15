<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsientoContable extends Model
{
    protected $table = 'asientos_contables';
    protected $primaryKey = 'idAsiento';
    public $timestamps = true;

    protected $fillable = [
        'Cliente_id',
        'fecha',
        'descripcion',
    ];

    protected $casts = [
        'fecha' => 'date', // esto convierte el string a objeto Carbon
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Cliente_id', 'idCliente');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoContable::class, 'AsientoContable_id');
    }
}
