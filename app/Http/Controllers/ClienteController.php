<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    /**
     * Mostrar todos los clientes
     */
    public function index(Request $request)
    {
        // 1. Inicia la consulta base y aplica el ordenamiento
        $query = Cliente::orderBy('RazonSocial');

        // 2. Obtener parámetros de filtro desde la solicitud (URL)
        $search = $request->input('search');
        $activo = $request->input('activo');
        $iva = $request->input('iva');

        // 3. Aplicar filtro de búsqueda general (Razon Social o CUIT)
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Búsqueda insensible a mayúsculas/minúsculas en RazonSocial
                $q->where('RazonSocial', 'LIKE', '%' . $search . '%')
                    // Búsqueda exacta o parcial en CUIT
                    ->orWhere('CUIT', 'LIKE', '%' . $search . '%');
            });
        }

        // 4. Aplicar filtro por estado (Activo: 1 o Inactivo: 0)
        // Usamos !== null y !== '' para que el filtro se aplique solo si se selecciona Activo o Inactivo
        if ($activo !== null && $activo !== '') {
            // Convertimos a entero para asegurar la comparación con el campo booleano/numérico
            $query->where('Activo', (int)$activo);
        }

        // 5. Aplicar filtro por Condición IVA (ej: RI, CF, EX, MT)
        if ($iva) {
            switch ($iva) {
                case 'RI':
                    $iva = 'Responsable Inscripto';
                    break;
                case 'CF':
                    $iva = 'Consumidor Final';
                    break;
                case 'EX':
                    $iva = 'Exento';
                    break;
                case 'MT':
                    $iva = 'Monotributista';
                    break;
                default:
                    $iva = null; // Si no es un valor válido, no aplicamos filtro
                    break;
            }
            $query->where('CondicionIVA', $iva);
        }

        // 6. Paginar los resultados.
        $clientes = $query->paginate(8)->appends($request->except('page'));

        // 7. Retornar la vista con los resultados filtrados.
        // La paginación en la vista usará ->appends() para mantener los filtros en la URL.
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Mostrar formulario para crear un cliente
     */
    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'CUIT' => 'nullable|string|unique:Cliente,CUIT|max:11',
            'TipoDocumento' => ['nullable', Rule::in(['CUIT', 'CUIL', 'DNI', 'Pasaporte', 'Otro'])],
            'NroDocumento' => 'nullable|string|max:15',
            'CondicionIVA' => ['nullable', Rule::in(['Responsable Inscripto', 'Monotributista', 'Consumidor Final', 'Exento'])],
            'NroIngBrutos' => 'nullable|string|max:20',
            'CondicionIngBrutos' => ['nullable', Rule::in(['Local', 'Multilateral'])],
            'ResponsabilidadSocial' => ['required', Rule::in(['Física', 'Jurídica'])],
            'RazonSocial' => 'required|string|max:150',
            'LimiteCredito' => 'nullable|numeric|min:0',
            'Activo' => 'nullable|boolean',
            'DomicilioFiscal' => 'nullable|string|max:255',
            'Localidad' => 'nullable|string|max:100',
            'Provincia' => 'nullable|string|max:100',
            'CodigoPostal' => 'nullable|string|max:10',
            'Pais' => 'nullable|string|max:100',
            'Email' => 'nullable|email|max:100',
            'Telefono' => 'nullable|string|max:50',
            'Observaciones' => 'nullable|string',
            'FechaAlta' => 'nullable|date',
        ]);

        // Ajustes para checkbox y enums
        $validated['Activo'] = $request->has('Activo') ? true : false;

        // Si checkbox de ResponsabilidadSocial viene marcado como Juridica, lo usamos, sino Física
        if ($request->input('ResponsabilidadSocial') === 'Jurídica') {
            $validated['ResponsabilidadSocial'] = 'Jurídica';
        } else {
            $validated['ResponsabilidadSocial'] = 'Física';
        }

        // Crear el cliente
        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }


    /**
     * Mostrar un cliente específico
     */
    // Ejemplo en el controlador ClienteController.php

    public function show(Cliente $cliente)
    {
        // Obtener los asientos asociados al cliente y paginarlos, ordenados por fecha
        $asientos = $cliente->asientosContables()
            ->with('movimientos.cuentaContable') // Precargar relaciones necesarias
            ->orderBy('fecha', 'desc')
            ->paginate(2); // Paginar de 10 en 10

        return view('clientes.show', [
            'cliente' => $cliente,
            'asientos' => $asientos, // Pasar la colección paginada
        ]);
    }
    /**
     * Mostrar formulario para editar un cliente
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar un cliente existente
     */
    public function update(Request $request, Cliente $cliente)
    {
        // ✅ Solo 'Activo' se maneja como checkbox
        $request->merge([
            'Activo' => $request->has('Activo'),
        ]);

        $validated = $request->validate([
            'RazonSocial' => 'required|string|max:150',
            'CUIT' => 'nullable|string|max:11|unique:Cliente,CUIT,' . $cliente->idCliente . ',idCliente',
            'TipoDocumento' => ['nullable', Rule::in(['CUIT', 'CUIL', 'DNI', 'Pasaporte', 'Otro'])],
            'NroDocumento' => 'nullable|string|max:15',
            'CondicionIVA' => 'nullable|string|max:50',
            'NroIngBrutos' => 'nullable|string|max:20',
            'CondicionIngBrutos' => 'nullable|string|max:50',
            // ✅ Ahora es un select, no un checkbox
            'ResponsabilidadSocial' => ['required', Rule::in(['Física', 'Jurídica'])],
            'LimiteCredito' => 'nullable|numeric|min:0',
            'Activo' => 'boolean',
            'DomicilioFiscal' => 'nullable|string|max:255',
            'Localidad' => 'nullable|string|max:100',
            'Provincia' => 'nullable|string|max:100',
            'CodigoPostal' => 'nullable|string|max:10',
            'Pais' => 'nullable|string|max:100',
            'Email' => 'nullable|email|max:100',
            'Telefono' => 'nullable|string|max:50',
            'Observaciones' => 'nullable|string',
            'FechaAlta' => 'nullable|date',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente ' . $cliente->RazonSocial . ' actualizado correctamente.');
    }

    /**
     * Eliminar un cliente
     */
    public function destroy(Cliente $cliente)
    {
        // Marcar como inactivo en vez de eliminar
        $cliente->update([
            'Activo' => false
        ]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente  ' . $cliente->RazonSocial . ' eliminado correctamente.');
    }

    /**
     * Restaurar un cliente inactivo
     */
    public function restore(Cliente $cliente)
    {
        $cliente->update([
            'Activo' => true
        ]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente ' . $cliente->RazonSocial . ' restaurado correctamente.');
    }
}
