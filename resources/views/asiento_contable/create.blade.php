<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/clientes.index.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <x-slot name="header">
        <h2 class="page-header">{{ __('Crear Asiento Contable') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="container">

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

                    <form action="{{ route('asiento_contable.store') }}" method="POST">
                        @csrf

                        <h3>Información General</h3>

                        <div class="form-group">
                            <label for="Cliente_id">Cliente</label>
                            <select name="Cliente_id" id="Cliente_id" required>
                                <option value="">-- Seleccione Cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->idCliente }}">
                                        {{ $cliente->RazonSocial }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="date" name="fecha" id="fecha" value="{{ old('fecha') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3">{{ old('descripcion') }}</textarea>
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
                                    {{-- Se inicializa con una fila vacía --}}
                                    <tr>
                                        <td>
                                            <select name="movimientos[0][CuentaContable_id]" class="select2-account" required>
                                                <option value="">-- Seleccione Cuenta --</option>
                                                @foreach($cuentas as $cuenta)
                                                    <option value="{{ $cuenta->idCuentaContable }}" data-tipo="{{ $cuenta->tipo }}">
                                                        {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="movimientos[0][tipo]" value="" class="tipo-variacion" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="movimientos[0][debe]" step="0.01" value="0" required>
                                        </td>
                                        <td>
                                            <input type="number" name="movimientos[0][haber]" step="0.01" value="0" required>
                                        </td>
                                        <td>
                                            <button type="button" class="btn-remove-row">Eliminar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" id="addRow">Agregar Movimiento</button>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn-create">Crear Asiento</button>
                            <a href="{{ route('asiento_contable.index') }}" class="btn-cancel">Cancelar</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#Cliente_id').select2({
                placeholder: "Selecciona un cliente",
                allowClear: true,
                width: '100%'
            });

            $('.select2-account').select2({
                placeholder: "Selecciona una cuenta contable",
                allowClear: true,
                width: '100%'
            });

            // Llenar el tipo automáticamente
            $(document).on('change', '.select2-account', function() {
                let tipo = $(this).find(':selected').data('tipo') || 'N/A';
                $(this).closest('tr').find('.tipo-variacion').val(tipo);
            });

            // Agregar nuevas filas
            let rowIndex = 1;
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
                $('.select2-account').select2({placeholder: "Selecciona una cuenta contable", allowClear: true, width: '100%'});
                rowIndex++;
            });

            // Eliminar fila
            $(document).on('click', '.btn-remove-row', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
</x-app-layout>
