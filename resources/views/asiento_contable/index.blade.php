<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/clientes.index.css') }}">

    {{-- Scripts y estilos para Select2 --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <x-slot name="header">
        <h2 class="page-header">{{ __('Asientos Contables') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="container">

            {{-- ... (Modal de éxito sin cambios) ... --}}
            @if (session('success'))
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

                        if (dialog && typeof dialog.showModal === 'function') {
                            dialog.showModal();
                            closeBtn.addEventListener('click', () => dialog.close());

                            dialog.addEventListener('click', (e) => {
                                const rect = dialog.getBoundingClientRect();
                                const isInDialog = (
                                    e.clientX >= rect.left &&
                                    e.clientX <= rect.right &&
                                    e.clientY >= rect.top &&
                                    e.clientY <= rect.bottom
                                );
                                if (!isInDialog) dialog.close();
                            });

                            setTimeout(() => dialog.close(), 3000);
                        }
                    });
                </script>
            @endif

            <div class="card">
                <div class="card-content">

                    <div class="controls-header">
                        <a href="{{ route('asiento_contable.create') }}" class="btn-create">Crear Asiento</a>

                        <form method="GET" action="{{ route('asiento_contable.index') }}" class="filter-form" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;">

                            {{-- 🔎 Filtro de Búsqueda General --}}
                            <div class="form-group" style="flex-grow: 1; min-width: 200px;">
                                <label for="search" style="display:block; font-size: 0.8em; margin-bottom: 3px;">Búsqueda (Descripción/ID)</label>
                                <input type="text" name="search" id="search" placeholder="Buscar..." class="filter-input"
                                    value="{{ request('search') }}">
                            </div>

                            {{-- 🧑‍💼 Filtro por Cliente --}}
                            <div class="form-group" style="flex-grow: 1; min-width: 180px;">
                                <label for="Cliente_id" style="display:block; font-size: 0.8em; margin-bottom: 3px;">Cliente</label>
                                <select name="Cliente_id" id="Cliente_id" class="select2-filter">
                                    <option value="">Todos los Clientes</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->idCliente }}"
                                                {{ request('Cliente_id') == $cliente->idCliente ? 'selected' : '' }}>
                                            {{ $cliente->RazonSocial }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 📅 Filtro Rango de Fechas --}}
                            <div class="form-group">
                                <label for="fecha_desde" style="display:block; font-size: 0.8em; margin-bottom: 3px;">Fecha Desde</label>
                                <input type="date" name="fecha_desde" id="fecha_desde" class="filter-input"
                                    value="{{ request('fecha_desde') }}">
                            </div>

                            <div class="form-group">
                                <label for="fecha_hasta" style="display:block; font-size: 0.8em; margin-bottom: 3px;">Fecha Hasta</label>
                                <input type="date" name="fecha_hasta" id="fecha_hasta" class="filter-input"
                                    value="{{ request('fecha_hasta') }}">
                            </div>

                            {{-- Botones de Acción --}}
                            <div class="form-group" style="margin-top: 5px;">
                                <button type="submit" class="filter-button" style="margin-right: 5px;">Aplicar Filtros</button>
                                @if (request()->filled('search') || request()->filled('Cliente_id') || request()->filled('fecha_desde') || request()->filled('fecha_hasta'))
                                    <a href="{{ route('asiento_contable.index') }}" class="reset-button">Limpiar Filtros</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            {{-- ... (Tabla de asientos sin cambios) ... --}}
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($asientos as $asiento)
                                    <tr>
                                        <td>{{ $asiento->idAsiento }}</td>
                                        <td>{{ $asiento->cliente->RazonSocial ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($asiento->fecha)->format('Y-m-d') }}</td>
                                        <td>{{ $asiento->descripcion }}</td>
                                        <td class="action-links">
                                            <a href="{{ route('asiento_contable.show', $asiento->idAsiento) }}" class="link-view">Ver</a>
                                            <a href="{{ route('asiento_contable.edit', $asiento->idAsiento) }}" class="link-edit">Editar</a>

                                            {{-- Botón Eliminar con dialog --}}
                                            <button type="button" class="link-delete"
                                                onclick="document.getElementById('deleteModal-{{ $asiento->idAsiento }}').showModal()">Eliminar</button>

                                            <dialog id="deleteModal-{{ $asiento->idAsiento }}">
                                                <form method="POST" action="{{ route('asiento_contable.destroy', $asiento->idAsiento) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <p>¿Estás seguro de eliminar este asiento contable?</p>
                                                    <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                                                        <button type="button" onclick="this.closest('dialog').close()">Cancelar</button>
                                                        <button type="submit">Confirmar</button>
                                                    </div>
                                                </form>
                                            </dialog>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="empty-state">No hay asientos contables registrados o no se encontraron resultados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination mt-2">
                        {{-- Preservar los filtros en la paginación --}}
                        {{ $asientos->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Inicialización de Select2 para el filtro de Clientes
            $('#Cliente_id').select2({
                placeholder: "Selecciona un cliente",
                allowClear: true,
                width: 'auto'

            });

            // Lógica para cerrar el modal de éxito (ya estaba)
            const dialog = document.getElementById('successDialog');
            const closeBtn = document.getElementById('closeSuccess');

            if (dialog && typeof dialog.showModal === 'function') {
                closeBtn.addEventListener('click', () => dialog.close());
            }

        });
    </script>
</x-app-layout>
