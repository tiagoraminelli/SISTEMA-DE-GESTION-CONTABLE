<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\AsientoContable;
use App\Models\MovimientoContable;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $clienteId = $request->input('cliente_id'); // filtrar por cliente si se pasa

        // -------------------------------------------------------------
        // 1. MÉTRICAS SIMPLES
        // -------------------------------------------------------------
        $clientesQuery = Cliente::query();
        if ($clienteId) {
            $clientesQuery->where('idCliente', $clienteId);
        }

        $totalClientes = $clientesQuery->count();
        $clientesActivos = $clientesQuery->activos()->count();
        $clientesInactivos = $clientesQuery->inactivos()->count();

        $asientosQuery = AsientoContable::query();
        if ($clienteId) {
            $asientosQuery->where('Cliente_id', $clienteId);
        }
        $totalAsientos = $asientosQuery->count();

        $movimientosQuery = MovimientoContable::query();
        if ($clienteId) {
            $movimientosQuery->whereHas('asiento', function ($q) use ($clienteId) {
                $q->where('Cliente_id', $clienteId);
            });
        }
        $totalMovimientos = $movimientosQuery->count();

        // -------------------------------------------------------------
        // 2. BALANCE POR TIPO DE CUENTA
        // -------------------------------------------------------------
        $balancePorTipoQuery = DB::table('movimientos_contables as mc')
            ->join('cuentas_contables as cc', 'mc.CuentaContable_id', '=', 'cc.idCuentaContable')
            ->select(
                'cc.tipo',
                DB::raw('SUM(mc.debe) as total_debe'),
                DB::raw('SUM(mc.haber) as total_haber')
            )
            ->groupBy('cc.tipo');

        if ($clienteId) {
            $balancePorTipoQuery->join('asientos_contables as ac', 'mc.AsientoContable_id', '=', 'ac.idAsiento')
                ->where('ac.Cliente_id', $clienteId);
        }

        $balancePorTipo = $balancePorTipoQuery->get()->keyBy('tipo');

        // -------------------------------------------------------------
        // 3. RESULTADO DEL EJERCICIO
        // -------------------------------------------------------------
        $totalesQuery = MovimientoContable::query();
        if ($clienteId) {
            $totalesQuery->whereHas('asiento', fn($q) => $q->where('Cliente_id', $clienteId));
        }

        $totales = $totalesQuery->select(
            DB::raw('SUM(debe) as total_debe'),
            DB::raw('SUM(haber) as total_haber')
        )->first();

        $resultadoEjercicio = $totales->total_debe - $totales->total_haber;

        // -------------------------------------------------------------
        // 4. BALANCE GENERAL RESUMIDO (ACTIVO / PASIVO / PATRIMONIO)
        // -------------------------------------------------------------
        $balanceGeneralQuery = DB::table('movimientos_contables as mc')
            ->join('cuentas_contables as cc', 'mc.CuentaContable_id', '=', 'cc.idCuentaContable')
            ->select('cc.tipo', DB::raw('SUM(mc.debe - mc.haber) as saldo'))
            ->groupBy('cc.tipo');

        if ($clienteId) {
            $balanceGeneralQuery->join('asientos_contables as ac', 'mc.AsientoContable_id', '=', 'ac.idAsiento')
                ->where('ac.Cliente_id', $clienteId);
        }

        $balanceGeneral = $balanceGeneralQuery->pluck('saldo', 'tipo');

        $activo  = $balanceGeneral[1] ?? 0;  // tipo 1 = activo
        $pasivo  = $balanceGeneral[2] ?? 0;  // tipo 2 = pasivo
        $patrimonio = $balanceGeneral[3] ?? ($activo - $pasivo);

        // -------------------------------------------------------------
        // 5. TOP CUENTAS MÁS USADAS
        // -------------------------------------------------------------
        $topCuentasQuery = DB::table('movimientos_contables as mc')
            ->join('cuentas_contables as cc', 'mc.CuentaContable_id', '=', 'cc.idCuentaContable')
            ->select(
                'mc.CuentaContable_id',
                'cc.codigo',
                'cc.nombre',
                DB::raw('COUNT(*) as usos')
            )
            ->groupBy('mc.CuentaContable_id', 'cc.codigo', 'cc.nombre')
            ->orderByDesc('usos')
            ->limit(5);

        $topCuentas = $topCuentasQuery->get();


        // -------------------------------------------------------------
        // 6. ÚLTIMOS ASIENTOS
        // -------------------------------------------------------------
        $ultimosAsientosQuery = AsientoContable::with(['cliente:idCliente,RazonSocial'])
            ->select('idAsiento', 'fecha', 'Cliente_id', 'descripcion')
            ->orderBy('fecha', 'desc')
            ->limit(5);

        if ($clienteId) {
            $ultimosAsientosQuery->where('Cliente_id', $clienteId);
        }

        $ultimosAsientos = $ultimosAsientosQuery->get();

        // -------------------------------------------------------------
        // 7. DEVOLVER A LA VISTA
        // -------------------------------------------------------------
        return view('dashboard.index', compact(
            'totalClientes',
            'clientesActivos',
            'clientesInactivos',
            'totalAsientos',
            'totalMovimientos',
            'balancePorTipo',
            'resultadoEjercicio',
            'activo',
            'pasivo',
            'patrimonio',
            'topCuentas',
            'ultimosAsientos',
            'clienteId' // opcional, para mantener el filtro en la vista
        ));
    }
}
