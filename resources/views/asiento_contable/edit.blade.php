<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/clientes.index.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <x-slot name="header">
        <h2 class="page-header">{{ __('Editar Asiento Contable') . ' #' . $asiento->idAsiento }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="container">

            {{-- ⚠️ Manejo de errores de validación y de lógica --}}
            @if ($errors->any())
                <div class="alert alert-danger" style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
                    <h4>Errores de Validación:</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <dialog id="successDialog">
                    <h3>Éxito</h3>
                    <p>{{ session('success') }}</p>
                    <div>
                        <button type="button" id="closeSuccess">Cerrar</button>
                    </div>
                </dialog>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const dialog = document.getElementById('successDialog');
                        const closeBtn = document.getElementById('closeSuccess');
                        if(dialog && typeof dialog.showModal === 'function') {
                            dialog.showModal();
                            closeBtn.addEventListener('click', () => dialog.close());
                            setTimeout(() => dialog.close(), 3000);
                        }
                    });
                </script>
            @endif

            <div class="card">
                <div class="card-content">

                    {{-- Formulario de Edición --}}
                    <form action="{{ route('asiento_contable.update', $asiento->idAsiento) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h3>Información General</h3>

                        <div class="form-group">
                            <label for="Cliente_id">Cliente</label>
                            <select name="Cliente_id" id="Cliente_id">
                                <option value="">-- Seleccione Cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->idCliente }}"
                                            {{ old('Cliente_id', $asiento->Cliente_id) == $cliente->idCliente ? 'selected' : '' }}>
                                        {{ $cliente->RazonSocial }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            @php
                                // Asegura que la fecha esté en formato YYYY-MM-DD
                                $fechaFormato = \Carbon\Carbon::parse(old('fecha', $asiento->fecha))->format('Y-m-d');
                            @endphp
                            <input type="date" name="fecha" id="fecha" value="{{ $fechaFormato }}" required>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3">{{ old('descripcion', $asiento->descripcion) }}</textarea>
                        </div>

                        <h3>Movimientos Contables</h3>
                        <div class="table-responsive">
                            <table class="data-table" id="movimientosTable">
                                <thead>
                                    <tr>
                                        <th>Cuenta Contable</th>
                                        <th>Variación Patrimonial</th>
                                        <th>Debe</th>
                                        <th>Haber</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Cargar movimientos existentes o datos antiguos (old) --}}
                                    @php
                                        // Si hay un error de validación, usamos old('movimientos'), si no, los movimientos del asiento
                                        $movimientosData = old('movimientos', $asiento->movimientos);
                                    @endphp

                                    @foreach($movimientosData as $index => $mov)
                                        @php
                                            // Normalizar el acceso a propiedades (objeto vs array)
                                            $cuentaContableId = is_array($mov) ? $mov['CuentaContable_id'] : $mov->CuentaContable_id;
                                            $debe = is_array($mov) ? $mov['debe'] : $mov->debe;
                                            $haber = is_array($mov) ? $mov['haber'] : $mov->haber;

                                            // Obtener el tipo de cuenta si está disponible (de la relación eager loaded)
                                            if (is_object($mov) && isset($mov->cuentaContable)) {
                                                $tipo = $mov->cuentaContable->tipo;
                                            } elseif (is_array($mov) && isset($mov['tipo'])) {
                                                $tipo = $mov['tipo'];
                                            } else {
                                                $tipo = '';
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <select name="movimientos[{{ $index }}][CuentaContable_id]" class="select2-account" required>
                                                    <option value="">-- Seleccione Cuenta --</option>
                                                    @foreach($cuentas as $cuenta)
                                                        <option value="{{ $cuenta->idCuentaContable }}"
                                                                data-tipo="{{ $cuenta->tipo }}"
                                                                {{ $cuentaContableId == $cuenta->idCuentaContable ? 'selected' : '' }}>
                                                            {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                {{-- Se llena automáticamente con JS al seleccionar la cuenta --}}
                                                <input type="text" name="movimientos[{{ $index }}][tipo]" value="{{ $tipo }}" class="tipo-variacion" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="movimientos[{{ $index }}][debe]" step="0.01" value="{{ $debe }}" required>
                                            </td>
                                            <td>
                                                <input type="number" name="movimientos[{{ $index }}][haber]" step="0.01" value="{{ $haber }}" required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn-remove-row">Eliminar</button>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <button type="button" id="addRow">Agregar Movimiento</button>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn-create">Guardar Cambios</button>
                            <a href="{{ route('asiento_contable.index') }}" class="btn-cancel">Cancelar</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Inicializar Select2 para Cliente
            $('#Cliente_id').select2({
                placeholder: "Selecciona un cliente",
                allowClear: true,
                width: '100%'
            });

            // Función de inicialización de Select2 para movimientos
            function initializeSelect2Accounts() {
                $('.select2-account').not('.select2-hidden-accessible').select2({
                    placeholder: "Selecciona una cuenta contable",
                    allowClear: true,
                    width: '100%'
                });
            }

            // Inicializar Select2 para todas las cuentas (existentes al cargar)
            initializeSelect2Accounts();

            // Llenar el tipo automáticamente (para movimientos nuevos y existentes)
            $(document).on('change', '.select2-account', function() {
                // El tipo se obtiene del atributo data-tipo de la opción seleccionada
                let tipo = $(this).find(':selected').data('tipo') || 'N/A';
                $(this).closest('tr').find('.tipo-variacion').val(tipo);
            });

            // 🛑 Lógica para auto-cargar la variación patrimonial para los movimientos existentes 🛑
            // Dispara el evento 'change' en los selects que ya tienen un valor
            $('#movimientosTable tbody tr').each(function() {
                const selectElement = $(this).find('.select2-account');
                if (selectElement.val()) {
                    selectElement.trigger('change');
                }
            });


            // Determinar el índice inicial basado en los movimientos existentes/cargados
            let rowIndex = $('#movimientosTable tbody tr').length;

            // Agregar nuevas filas
            $('#addRow').click(function() {
                let newRow = `
                    <tr>
                        <td>
                            <select name="movimientos[${rowIndex}][CuentaContable_id]" class="select2-account" required>
                                <option value="">-- Seleccione Cuenta --</option>
                                @foreach($cuentas as $cuenta)
                                    <option value="{{ $cuenta->idCuentaContable }}" data-tipo="{{ $cuenta->tipo }}">
                                        {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="movimientos[${rowIndex}][tipo]" value="" class="tipo-variacion" readonly>
                        </td>
                        <td>
                            <input type="number" name="movimientos[${rowIndex}][debe]" step="0.01" value="0" required>
                        </td>
                        <td>
                            <input type="number" name="movimientos[${rowIndex}][haber]" step="0.01" value="0" required>
                        </td>
                        <td>
                            <button type="button" class="btn-remove-row">Eliminar</button>
                        </td>
                    </tr>
                `;
                $('#movimientosTable tbody').append(newRow);
                initializeSelect2Accounts(); // Re-inicializar Select2 para la nueva fila
                rowIndex++;
            });

            // Eliminar fila
            $(document).on('click', '.btn-remove-row', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
</x-app-layout>
