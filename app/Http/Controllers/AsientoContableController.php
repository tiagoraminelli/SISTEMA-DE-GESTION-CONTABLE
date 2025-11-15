<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\AsientoContable;
use App\Models\Cliente;
use App\Models\CuentaContable;
use App\Models\MovimientoContable;
use Illuminate\Http\Request;


class AsientoContableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener todos los clientes para usar en el filtro Select2 de la vista
        $clientes = Cliente::orderBy('RazonSocial')->get();

        // 1. Iniciar la consulta Eloquent para AsientoContable
        $query = AsientoContable::query();

        // 2. Aplicar filtro por Cliente
        if ($request->filled('Cliente_id')) {
            $query->where('Cliente_id', $request->Cliente_id);
        }

        // 3. Aplicar filtro por Rango de Fechas
        if ($request->filled('fecha_desde')) {
            // whereDate asegura que se filtre desde la medianoche de esa fecha
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            // whereDate asegura que se filtre hasta la medianoche de esa fecha
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        // 4. Aplicar filtro de Búsqueda General (ID, Descripción o Cliente)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                // Búsqueda por ID (si el valor es numérico o se quiere buscar así)
                $q->where('idAsiento', 'like', "%{$search}%")
                    // Búsqueda por Descripción
                    ->orWhere('descripcion', 'like', "%{$search}%")
                    // Búsqueda por Razón Social del Cliente (asumiendo que tienes la relación 'cliente')
                    ->orWhereHas('cliente', function ($qCliente) use ($search) {
                        $qCliente->where('RazonSocial', 'like', "%{$search}%");
                    });
            });
        }

        // 5. Ejecutar la consulta, cargar la relación 'cliente' y paginar
        $asientos = $query->with('cliente')
            ->orderBy('fecha', 'desc')
            ->paginate(10); // Mantengo tu paginación de 10

        // Retornar la vista con los asientos filtrados y la lista de clientes para el filtro
        return view('asiento_contable.index', compact('asientos', 'clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('RazonSocial')->get();
        $cuentas = CuentaContable::orderBy('nombre')->get();
        return view('asiento_contable.create', compact('clientes', 'cuentas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'Cliente_id' => 'nullable|exists:Cliente,idCliente',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string|max:255',
            'movimientos' => 'required|array|min:1',
            'movimientos.*.CuentaContable_id' => 'required|exists:cuentas_contables,idCuentaContable',
            'movimientos.*.debe' => 'required|numeric|min:0',
            'movimientos.*.haber' => 'required|numeric|min:0',
        ]);

        // Validar que el asiento cuadre (sumatoria debe = sumatoria haber)
        $totalDebe = array_sum(array_column($request->movimientos, 'debe'));
        $totalHaber = array_sum(array_column($request->movimientos, 'haber'));

        if ($totalDebe != $totalHaber) {
            return back()->withErrors(['error' => 'El asiento no cuadra: total debe debe ser igual a total haber.'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Crear el asiento contable
            $asiento = AsientoContable::create([
                'Cliente_id' => $request->Cliente_id,
                'fecha' => $request->fecha,
                'descripcion' => $request->descripcion,
            ]);

            // Crear los movimientos contables
            foreach ($request->movimientos as $mov) {
                if ($mov['debe'] > 0 || $mov['haber'] > 0) {
                    MovimientoContable::create([
                        'AsientoContable_id' => $asiento->idAsiento,
                        'CuentaContable_id'  => $mov['CuentaContable_id'],
                        'debe'               => $mov['debe'],
                        'haber'              => $mov['haber'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('asiento_contable.index')
                ->with('success', 'Asiento contable creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear el asiento: ' . $e->getMessage()])
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $asiento = AsientoContable::with('cliente', 'movimientos')->findOrFail($id);
        return view('asiento_contable.show', compact('asiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Traer asiento con movimientos y sus cuentas relacionadas (necesario para precargar la tabla)
        $asiento = AsientoContable::with('movimientos.cuentaContable')->findOrFail($id);

        // Listado de clientes para el select de la info general
        $clientes = Cliente::orderBy('RazonSocial')->get();

        // Listado de cuentas para los select de movimientos
        $cuentas = CuentaContable::orderBy('nombre')->get();

        return view('asiento_contable.edit', compact('asiento', 'clientes', 'cuentas'));
    }

    // 💾 Método para guardar los cambios
    public function update(Request $request, $id)
    {
        // 1. Validación de datos y estructura
        $request->validate([
            'Cliente_id' => 'nullable|exists:Cliente,idCliente',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string|max:255',
            'movimientos' => 'required|array|min:1',
            'movimientos.*.CuentaContable_id' => 'required|exists:cuentas_contables,idCuentaContable',
            'movimientos.*.debe' => 'required|numeric|min:0',
            'movimientos.*.haber' => 'required|numeric|min:0',
        ]);

        // 2. Validación contable: Total Debe debe ser igual al Total Haber
        $totalDebe = array_sum(array_column($request->movimientos, 'debe'));
        $totalHaber = array_sum(array_column($request->movimientos, 'haber'));

        if ($totalDebe != $totalHaber) {
            return back()->withErrors(['error' => 'El asiento contable no está equilibrado: total debe ≠ total haber'])
                ->withInput();
        }

        // 3. Inicio de la Transacción de Base de Datos
        DB::beginTransaction();

        try {
            $asiento = AsientoContable::findOrFail($id);

            // 4. Actualizar información general del asiento
            $asiento->update([
                'Cliente_id' => $request->Cliente_id,
                'fecha' => $request->fecha,
                'descripcion' => $request->descripcion,
            ]);

            // 5. Actualizar movimientos: Estrategia de reemplazo (delete + insert)

            // Primero, eliminamos todos los movimientos existentes asociados
            $asiento->movimientos()->delete();

            // Luego, insertamos los nuevos movimientos enviados en el formulario
            foreach ($request->movimientos as $mov) {
                // Se asegura de no guardar filas vacías si el usuario dejó 0 en Debe y Haber
                if ($mov['debe'] > 0 || $mov['haber'] > 0) {
                    MovimientoContable::create([
                        'AsientoContable_id' => $asiento->idAsiento,
                        'CuentaContable_id' => $mov['CuentaContable_id'],
                        'debe' => $mov['debe'],
                        'haber' => $mov['haber'],
                    ]);
                }
            }

            // 6. Si todo fue exitoso, confirmamos la transacción
            DB::commit();

            return redirect()->route('asiento_contable.index')
                ->with('success', 'Asiento contable actualizado correctamente.');
        } catch (\Exception $e) {
            // 7. Si hay un error, revertimos todos los cambios
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar el asiento: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $asiento = AsientoContable::findOrFail($id);
        $asiento->delete();

        return redirect()->route('asiento_contable.index')->with('success', 'Asiento contable eliminado correctamente.');
    }
}
