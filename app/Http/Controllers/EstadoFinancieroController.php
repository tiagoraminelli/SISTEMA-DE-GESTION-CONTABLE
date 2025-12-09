<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuentaContable;
use App\Models\AsientoContable;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class EstadoFinancieroController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener y preparar datos del filtro (incluyendo fechas)
        $clienteId = $request->input('cliente_id');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $clientes = Cliente::orderBy('RazonSocial')->get(['idCliente', 'RazonSocial']);

        // ... (Tu lógica para obtener el objeto $cliente sigue igual) ...
        $cliente = null;
        if ($clienteId) {
            $cliente = Cliente::find($clienteId);
            if (!$cliente) {
                $clienteId = null;
            }
        }
        // Inicializar todas las colecciones y totales a cero/vacío
        $cuentasConSaldo = collect();
        $totalActivo = 0;
        $totalPasivo = 0;
        $totalPatrimonio = 0;
        $activoCorriente = collect();
        $activoNoCorriente = collect();
        $pasivoCorriente = collect();
        $pasivoNoCorriente = collect();
        $patrimonio = collect();

        $saldos = collect(); // Definir $saldos por si no se entra al if

        // El reporte solo se genera si hay un cliente seleccionado
        if ($clienteId) {

            // 2. Consulta EFICIENTE para obtener saldos por cuenta, cliente y PERIODO
            $saldosQuery = DB::table('cuentas_contables as cc')
                ->select(
                    'cc.idCuentaContable',
                    'cc.nombre',
                    'cc.tipo',
                    'cc.subtipo',
                    DB::raw('COALESCE(SUM(mc.debe), 0) as total_debe'),
                    DB::raw('COALESCE(SUM(mc.haber), 0) as total_haber')
                )
                ->join('movimientos_contables as mc', 'cc.idCuentaContable', '=', 'mc.CuentaContable_id')
                ->join('asientos_contables as ac', 'mc.AsientoContable_id', '=', 'ac.idAsiento')
                ->where('ac.Cliente_id', $clienteId); // FILTRO POR CLIENTE

            // 🚨 FILTRO POR PERIODO: Añadir las condiciones WHERE DATE 🚨
            if ($fechaInicio) {
                // Asume que la columna de fecha en asientos_contables se llama 'fecha'
                $saldosQuery->where('ac.fecha', '>=', $fechaInicio);
            }
            if ($fechaFin) {
                // Asume que la columna de fecha en asientos_contables se llama 'fecha'
                $saldosQuery->where('ac.fecha', '<=', $fechaFin);
            }
            // ---------------------------------------------------------------

            $saldos = $saldosQuery
                ->groupBy('cc.idCuentaContable', 'cc.nombre', 'cc.tipo', 'cc.subtipo')
                ->get();

            // Variables para el Resultado del Ejercicio
            $resultadoEjercicio = 0;

            // 3. Calcular saldos individuales y clasificar (Resto de la lógica sigue igual)
            foreach ($saldos as $cuenta) {
                $saldo = 0;

                // Determinar el saldo según la naturaleza de la cuenta
                if (in_array($cuenta->tipo, ['Activo', 'Egreso'])) {
                    $saldo = $cuenta->total_debe - $cuenta->total_haber;
                } elseif (in_array($cuenta->tipo, ['Pasivo', 'Patrimonio Neto', 'Ingreso'])) {
                    $saldo = $cuenta->total_haber - $cuenta->total_debe;
                }

                // Solo si hay saldo significativo
                if (abs($saldo) > 0.005) {
                    $cuenta->saldo = $saldo;

                    if ($cuenta->tipo === 'Activo') {
                        $cuentasConSaldo->push($cuenta);
                        $totalActivo += $saldo;
                        if ($cuenta->subtipo === 'Corriente') {
                            $activoCorriente->push($cuenta);
                        } else {
                            $activoNoCorriente->push($cuenta);
                        }
                    } elseif ($cuenta->tipo === 'Pasivo') {
                        $cuentasConSaldo->push($cuenta);
                        $totalPasivo += $saldo;
                        if ($cuenta->subtipo === 'Corriente') {
                            $pasivoCorriente->push($cuenta);
                        } else {
                            $pasivoNoCorriente->push($cuenta);
                        }
                    } elseif ($cuenta->tipo === 'Patrimonio Neto') {
                        $patrimonio->push($cuenta);
                        $totalPatrimonio += $saldo;
                    } elseif (in_array($cuenta->tipo, ['Ingreso', 'Egreso'])) {
                        $resultadoEjercicio += $saldo;
                    }
                }
            }

            // 4. Integrar el Resultado del Ejercicio al Patrimonio Neto
            if (abs($resultadoEjercicio) > 0.005) {
                $nombreResultado = $resultadoEjercicio >= 0 ? 'Resultado del Ejercicio (Utilidad)' : 'Resultado del Ejercicio (Pérdida)';

                $cuentaResultado = (object) [
                    'nombre' => $nombreResultado,
                    'saldo' => $resultadoEjercicio,
                ];
                $patrimonio->push($cuentaResultado);
                $totalPatrimonio += $resultadoEjercicio;
            }
        } // Fin if ($clienteId)

        // 5. Devolver datos a la vista
        return view('estado_financiero.index', compact(
            'clientes',
            'clienteId',
            'cliente',
            'fechaInicio', // 🚨 PASAR FECHAS A LA VISTA 🚨
            'fechaFin',    // 🚨 PASAR FECHAS A LA VISTA 🚨
            'activoCorriente',
            'activoNoCorriente',
            'pasivoCorriente',
            'pasivoNoCorriente',
            'patrimonio',
            'totalActivo',
            'totalPasivo',
            'totalPatrimonio'

        ));
    }

    public function resultados(Request $request)
    {
        $clienteId   = $request->input('cliente_id');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin    = $request->input('fecha_fin');

        // Obtener lista de clientes para el dropdown
        $clientes = Cliente::orderBy('RazonSocial')->get(['idCliente', 'RazonSocial']);

        $ingresos = collect();
        $egresos  = collect();
        $totalIngresos = 0;
        $totalEgresos = 0;
        $resultadoEjercicio = 0;

        if ($clienteId) {
            // INGRESOS
            $ingresos = DB::table('cuentas_contables as cc')
                ->select('cc.nombre', DB::raw('SUM(mc.haber - mc.debe) as saldo'))
                ->join('movimientos_contables as mc', 'cc.idCuentaContable', '=', 'mc.CuentaContable_id')
                ->join('asientos_contables as ac', 'mc.AsientoContable_id', '=', 'ac.idAsiento')
                ->where('ac.Cliente_id', $clienteId)
                ->where('cc.tipo', 'Resultado Positivo');

            if ($fechaInicio) $ingresos->where('ac.fecha', '>=', $fechaInicio);
            if ($fechaFin)    $ingresos->where('ac.fecha', '<=', $fechaFin);

            $ingresos = $ingresos->groupBy('cc.nombre')->get();
            $totalIngresos = $ingresos->sum('saldo');

            // EGRESOS
            $egresos = DB::table('cuentas_contables as cc')
                ->select('cc.nombre', DB::raw('SUM(mc.debe - mc.haber) as saldo'))
                ->join('movimientos_contables as mc', 'cc.idCuentaContable', '=', 'mc.CuentaContable_id')
                ->join('asientos_contables as ac', 'mc.AsientoContable_id', '=', 'ac.idAsiento')
                ->where('ac.Cliente_id', $clienteId)
                ->where('cc.tipo', 'Resultado Negativo');

            if ($fechaInicio) $egresos->where('ac.fecha', '>=', $fechaInicio);
            if ($fechaFin)    $egresos->where('ac.fecha', '<=', $fechaFin);

            $egresos = $egresos->groupBy('cc.nombre')->get();
            $totalEgresos = $egresos->sum('saldo');

            $resultadoEjercicio = $totalIngresos - $totalEgresos;
        }

        return view('estado_financiero.resultados', compact(
            'ingresos',
            'egresos',
            'totalIngresos',
            'totalEgresos',
            'resultadoEjercicio',
            'clienteId',
            'fechaInicio',
            'fechaFin',
            'clientes' // <-- agregar $clientes
        ));
    }

public function ecuacionDinamica(Request $request)
{
    $clienteId   = $request->input('cliente_id');
    $fechaInicio = $request->input('fecha_inicio');
    $fechaFin    = $request->input('fecha_fin');

    $cliente = null;
    $cuentasAgrupadas = []; // Detalle de las cuentas agrupadas por tipo

    // Definimos los tipos de cuenta
    $tiposContables = [
        'Activo', 'Pasivo', 'Patrimonio Neto',
        'Resultado Positivo', 'Resultado Negativo'
    ];

    // Inicialización de los totales para la Ecuación Fundamental
    $totales = array_fill_keys(array_map('strtolower', $tiposContables), 0.0);

    // 1. Cargar datos del cliente con sus asientos filtrando fechas
    if ($clienteId) {
        $cliente = Cliente::with(['asientosContables' => function($query) use ($fechaInicio, $fechaFin) {
            if ($fechaInicio) $query->where('fecha', '>=', $fechaInicio);
            if ($fechaFin) $query->where('fecha', '<=', $fechaFin);
            $query->with('movimientos.cuentaContable');
        }])->find($clienteId);

        if ($cliente && $cliente->asientosContables->isNotEmpty()) {
            // 2. Acumulación de Debe/Haber por cuenta
            foreach ($cliente->asientosContables as $asiento) {
                foreach ($asiento->movimientos as $mov) {
                    $cuenta = $mov->cuentaContable;
                    if (!$cuenta) continue;

                    $nombreCuenta = $cuenta->nombre;
                    $tipo = strtolower($cuenta->tipo);

                    if (!isset($cuentasAgrupadas[$tipo][$nombreCuenta])) {
                        $cuentasAgrupadas[$tipo][$nombreCuenta] = [
                            'nombre' => $nombreCuenta,
                            'debe'   => 0.0,
                            'haber'  => 0.0,
                        ];
                    }

                    $cuentasAgrupadas[$tipo][$nombreCuenta]['debe']  += $mov->debe;
                    $cuentasAgrupadas[$tipo][$nombreCuenta]['haber'] += $mov->haber;
                }
            }

            // 3. Calcular saldo por cuenta según naturaleza y acumular totales
            foreach ($cuentasAgrupadas as $tipo => $cuentas) {
                foreach ($cuentas as $nombre => $data) {
                    $saldo = 0.0;

                    if (in_array($tipo, ['activo', 'resultado negativo'])) {
                        $saldo = $data['debe'] - $data['haber'];
                    } elseif (in_array($tipo, ['pasivo', 'patrimonio neto', 'resultado positivo'])) {
                        $saldo = $data['haber'] - $data['debe'];
                    }

                    $cuentasAgrupadas[$tipo][$nombre]['saldo'] = $saldo;
                    $totales[$tipo] += $saldo;
                }
            }
        }
    }

    // Lista de clientes para el filtro
    $clientes = Cliente::all();

    // PASO CLAVE: pasar $cuentasAgrupadas a la vista en vez de $movimientosAgrupados
    return view('estado_financiero.detalle', compact(
        'cuentasAgrupadas',
        'totales',
        'cliente',
        'clientes',
        'clienteId',
        'fechaInicio',
        'fechaFin'
    ));
}


}
