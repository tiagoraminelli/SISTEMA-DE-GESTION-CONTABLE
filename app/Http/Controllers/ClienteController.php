<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /**
     * Listado con filtros
     */
    public function index(Request $request)
    {
        $query = Cliente::query()->orderBy('RazonSocial');

        // Filtro: búsqueda general
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('RazonSocial', 'LIKE', "%{$search}%")
                    ->orWhere('CUIT', 'LIKE', "%{$search}%");
            });
        }

        // Filtro: activo (0 / 1)
        if ($request->filled('activo')) {
            $query->where('Activo', (int) $request->input('activo'));
        }

        // Filtro: IVA (siglas)
        if ($request->filled('iva')) {
            $ivaMap = [
                'RI' => 'Responsable Inscripto',
                'CF' => 'Consumidor Final',
                'EX' => 'Exento',
                'MT' => 'Monotributista',
            ];

            $iva = $ivaMap[$request->iva] ?? null;

            if ($iva) {
                $query->where('CondicionIVA', $iva);
            }
        }

        $clientes = $query->paginate(8)->appends($request->query());

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Crear Cliente
     */
    public function store(Request $request)
    {
        $validated = $this->validateCliente($request);

        $validated['Activo'] = $request->boolean('Activo');

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Ver Cliente + sus asientos
     */
    public function show(Cliente $cliente)
    {
        $asientos = $cliente->asientosContables()
            ->with('movimientos.cuentaContable')
            ->paginate(2);

        return view('clientes.show', compact('cliente', 'asientos'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar Cliente
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validated = $this->validateCliente($request, $cliente);
        $validated['Activo'] = $request->boolean('Activo');

        $cliente->update($validated);

        return redirect()->route('clientes.index')
            ->with('success', "Cliente {$cliente->RazonSocial} actualizado correctamente.");
    }

    /**
     * Baja lógica (Soft Delete)
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->update(['Activo' => false]);

        return redirect()->route('clientes.index')
            ->with('success', "Cliente {$cliente->RazonSocial} eliminado correctamente.");
    }

    /**
     * Restaurar
     */
    public function restore(Cliente $cliente)
    {
        $cliente->update(['Activo' => true]);

        return redirect()->route('clientes.index')
            ->with('success', "Cliente {$cliente->RazonSocial} restaurado correctamente.");
    }

    /**
     * Validación común para store/update
     */


    private function validateCliente(Request $request, Cliente $cliente = null)
    {
        return $request->validate(
            [
                'RazonSocial' => 'required|string|min:3|max:150',
                'CUIT' => [
                    'nullable',
                    'string',
                    'min:11',
                    'max:11',
                    Rule::unique('Cliente', 'CUIT')->ignore($cliente->idCliente ?? null, 'idCliente'),
                ],
                'TipoDocumento' => ['nullable', Rule::in(['CUIT', 'CUIL', 'DNI', 'Pasaporte', 'Otro'])],
                'NroDocumento' => 'nullable|string|min:7|max:9',
                'CondicionIVA' => ['nullable', Rule::in(['Responsable Inscripto', 'Monotributista', 'Consumidor Final', 'Exento'])],
                'NroIngBrutos' => 'nullable|string|min:7|max:20',
                'CondicionIngBrutos' => ['nullable', Rule::in(['Local', 'Multilateral'])],
                'ResponsabilidadSocial' => ['required', Rule::in(['Física', 'Jurídica'])],
                'LimiteCredito' => 'required|numeric|min:0',
                'DomicilioFiscal' => 'nullable|string|min:3|max:255',
                'Localidad' => 'nullable|string|min:3|max:100',
                'Provincia' => 'nullable|string|min:3|max:100',
                'CodigoPostal' => 'nullable|numeric|min:1000|max:9999',
                'Pais' => 'nullable|string|max:100',
                'Email' => 'nullable|email|max:100',
                'Telefono' => 'nullable|string|min:3|max:50',
                'Observaciones' => 'nullable|string',
                'FechaAlta' => 'nullable|date',
            ],
            [
                // Razon Social
                'RazonSocial.required' => 'La razón social es obligatoria.',
                'RazonSocial.max' => 'La razón social no puede superar los 150 caracteres.',
                'RazonSocial.min' => 'La razón social debe tener al menos 3 caracteres.',

                // CUIT
                'CUIT.max' => 'El CUIT no puede tener más de 11 dígitos.',
                'CUIT.unique' => 'El CUIT ingresado ya está registrado en el sistema.',
                'CUIT.min' => 'El CUIT debe tener al menos 11 dígitos.',

                // Tipo de documento
                'TipoDocumento.in' => 'El tipo de documento seleccionado no es válido.',

                // Número de documento
                'NroDocumento.max' => 'El número de documento no puede superar los 9 caracteres.',
                'NroDocumento.min' => 'El número de documento debe tener al menos 7 caracteres.',

                // IVA
                'CondicionIVA.in' => 'La condición de IVA seleccionada no es válida.',

                // Ingresos Brutos
                'NroIngBrutos.max' => 'El número de Ingresos Brutos no puede superar los 20 caracteres.',
                'CondicionIngBrutos.in' => 'La condición de Ingresos Brutos no es válida.',

                // Responsabilidad Social
                'ResponsabilidadSocial.required' => 'Debe seleccionar el tipo de responsabilidad social.',
                'ResponsabilidadSocial.in' => 'La responsabilidad social seleccionada no es válida.',

                // Limite de crédito
                'LimiteCredito.required' => 'El límite de crédito es obligatorio.',

                // Límite de crédito
                'LimiteCredito.numeric' => 'El límite de crédito debe ser un número válido.',
                'LimiteCredito.min' => 'El límite de crédito no puede ser negativo.',

                // Campos generales
                'DomicilioFiscal.max' => 'El domicilio fiscal no puede superar los 255 caracteres.',
                'DomicilioFiscal.min' => 'El domicilio fiscal debe tener al menos 3 caracteres.',
                'Localidad.max' => 'La localidad no puede superar los 100 caracteres.',
                'Localidad.min' => 'La localidad debe tener al menos 3 caracteres.',
                'Provincia.max' => 'La provincia no puede superar los 100 caracteres.',
                'Provincia.min' => 'La provincia debe tener al menos 3 caracteres.',

                'CodigoPostal.max' => 'El código postal no puede superar los 4 caracteres.',
                'CodigoPostal.numeric' => 'El código postal debe ser un número válido.',
                'CodigoPostal.min' => 'El código postal debe tener al menos 4 caracteres.',

                'Pais.max' => 'El país no puede superar los 100 caracteres.',

                // Email
                'Email.email' => 'El correo electrónico no tiene un formato válido.',
                'Email.max' => 'El correo electrónico no puede superar los 100 caracteres.',

                // Teléfono
                'Telefono.max' => 'El teléfono no puede superar los 50 caracteres.',
                'Telefono.min' => 'El teléfono debe tener al menos 3 caracteres.',


                // Observaciones
                'Observaciones.string' => 'Las observaciones deben ser texto válido.',

                // Fecha
                'FechaAlta.date' => 'La fecha de alta debe ser una fecha válida.',
            ]
        );
    }
}
