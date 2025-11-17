<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\AsientoContable;
use App\Models\MovimientoContable;
use App\Models\CuentaContable;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClientes = Cliente::count();
        $totalAsientos = AsientoContable::count();
        $totalMovimientos = MovimientoContable::count();

        $cuentas = CuentaContable::with('movimientos')->get();
        $balancePorTipo = $cuentas->groupBy('tipo')->map(function($grupo) {
            return [
                'debe' => $grupo->sum(fn($c) => $c->movimientos->sum('debe')),
                'haber' => $grupo->sum(fn($c) => $c->movimientos->sum('haber')),
            ];
        });

        $clientesActivos = Cliente::where('Activo', true)->count();
        $clientesInactivos = Cliente::where('Activo', false)->count();

        $ultimosAsientos = AsientoContable::with('cliente')
            ->orderBy('fecha', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalClientes',
            'totalAsientos',
            'totalMovimientos',
            'balancePorTipo',
            'clientesActivos',
            'clientesInactivos',
            'ultimosAsientos'
        ));
    }
}
