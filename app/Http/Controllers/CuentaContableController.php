<?php

namespace App\Http\Controllers;

use App\Models\CuentaContable;
use Illuminate\Http\Request;

class CuentaContableController extends Controller
{
    /**
     * Mostrar listado de cuentas contables con filtros y paginación
     */
    public function index(Request $request)
    {
        $cuentas = CuentaContable::query()
            ->when($request->search, function($query, $search){
                $query->where('nombre', 'like', "%{$search}%")
                      ->orWhere('codigo', 'like', "%{$search}%");
            })
            ->when($request->tipo, function($query, $tipo){
                $query->where('tipo', $tipo);
            })
            ->when($request->subtipo, function($query, $subtipo){
                $query->where('subtipo', $subtipo);
            })
            ->orderBy('codigo', 'asc')
            ->paginate(15)
            ->appends($request->query());

        return view('cuentas_contables.index', compact('cuentas'));
    }

    /**
     * Mostrar formulario para crear una nueva cuenta
     */
    public function create()
    {
        $tipos = ['Activo','Pasivo','Patrimonio Neto','Resultado Positivo','Resultado Negativo'];
        $subtipos = ['Corriente','No Corriente'];
        return view('cuentas_contables.create', compact('tipos','subtipos'));
    }

    /**
     * Guardar nueva cuenta contable
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|unique:cuentas_contables,codigo',
            'nombre' => 'required',
            'tipo' => 'required',
            'subtipo' => 'nullable',
            'rubro' => 'nullable',
            'nivel' => 'required|integer|min:1',
            'descripcion' => 'nullable',
            'estado' => 'required|boolean',
        ]);

        CuentaContable::create($request->all());

        return redirect()->route('cuentas_contables.index')->with('success', 'Cuenta contable creada correctamente.');
    }

    /**
     * Mostrar una cuenta contable específica
     */
    public function show($id)
    {
        $cuenta = CuentaContable::findOrFail($id);
        return view('cuentas_contables.show', compact('cuenta'));
    }

    /**
     * Mostrar formulario para editar una cuenta contable
     */
    public function edit($id)
    {
        $cuenta = CuentaContable::findOrFail($id);
        $tipos = ['Activo','Pasivo','Patrimonio Neto','Resultado Positivo','Resultado Negativo'];
        $subtipos = ['Corriente','No Corriente'];
        return view('cuentas_contables.edit', compact('cuenta','tipos','subtipos'));
    }

    /**
     * Actualizar cuenta contable
     */
    public function update(Request $request, $id)
    {
        $cuenta = CuentaContable::findOrFail($id);

        $request->validate([
            'codigo' => 'required|unique:cuentas_contables,codigo,'.$cuenta->idCuentaContable.',idCuentaContable',
            'nombre' => 'required',
            'tipo' => 'required',
            'subtipo' => 'nullable',
            'rubro' => 'nullable',
            'nivel' => 'required|integer|min:1',
            'descripcion' => 'nullable',
            'estado' => 'required|boolean',
        ]);

        $cuenta->update($request->all());

        return redirect()->route('cuentas_contables.index')->with('success', 'Cuenta contable actualizada correctamente.');
    }

    /**
     * Eliminar cuenta contable
     */
    public function destroy($id)
    {
        $cuenta = CuentaContable::findOrFail($id);
        $cuenta->delete();

        return redirect()->route('cuentas_contables.index')->with('success', 'Cuenta contable eliminada correctamente.');
    }
}
