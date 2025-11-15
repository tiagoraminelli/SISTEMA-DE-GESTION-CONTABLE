<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoContable extends Model
{
    protected $table = 'movimientos_contables';
    protected $primaryKey = 'idMovimiento';
    public $timestamps = true;

    protected $fillable = [
        'AsientoContable_id',
        'CuentaContable_id',
        'debe',
        'haber',
    ];

    public function asiento()
    {
        return $this->belongsTo(AsientoContable::class, 'AsientoContable_id', 'idAsiento');
    }

    public function cuentaContable()
    {
        return $this->belongsTo(CuentaContable::class, 'CuentaContable_id', 'idCuentaContable');
    }
}
