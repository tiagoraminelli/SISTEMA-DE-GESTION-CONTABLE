<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/cuentas_contables.index.css') }}">
    <x-slot name="header">
        <h2 class="page-header">{{ __('Cuentas Contables') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="container">

            {{-- Modal nativo para mensajes de éxito --}}
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

                    {{-- Controles y filtros --}}
                    <div class="controls-header">
                        <a href="{{ route('cuentas_contables.create') }}" class="btn-create">Crear Cuenta</a>

                        <form method="GET" action="{{ route('cuentas_contables.index') }}" class="filter-form">
                            <input type="text" name="search" placeholder="Buscar por nombre o código" class="filter-input"
                                value="{{ request('search') }}">
                            <select name="tipo" class="filter-select">
                                <option value="">Tipo (Todos)</option>
                                @foreach(['Activo','Pasivo','Patrimonio Neto','Resultado Positivo','Resultado Negativo'] as $tipo)
                                    <option value="{{ $tipo }}" {{ request('tipo') === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endforeach
                            </select>
                            <select name="subtipo" class="filter-select">
                                <option value="">Subtipo (Todos)</option>
                                @foreach(['Corriente','No Corriente'] as $subtipo)
                                    <option value="{{ $subtipo }}" {{ request('subtipo') === $subtipo ? 'selected' : '' }}>{{ $subtipo }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="filter-button">Filtrar</button>
                            @if (request()->has('search') || request()->has('tipo') || request()->has('subtipo'))
                                <a href="{{ route('cuentas_contables.index') }}" class="reset-button">Limpiar</a>
                            @endif
                        </form>
                    </div>

                    {{-- Tabla de cuentas contables agrupadas por tipo y rubro --}}
                    <div class="table-responsive">
                        @php
                            $tipos = $cuentas->groupBy('tipo');
                        @endphp

                        @foreach($tipos as $tipo => $cuentasPorTipo)
                            <h3 class="tipo-header mt-4">{{ $tipo }}</h3>

                            @php
                                $rubros = $cuentasPorTipo->groupBy('rubro');
                            @endphp

                            @foreach($rubros as $rubro => $cuentasPorRubro)
                                <h4 class="rubro-header">{{ $rubro ?? 'Sin Rubro' }}</h4>
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Subtipo</th>
                                            <th>Nivel</th>
                                            <th>Descripción</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cuentasPorRubro as $cuenta)
                                            <tr>
                                                <td>{{ $cuenta->codigo }}</td>
                                                <td>{{ $cuenta->nombre }}</td>
                                                <td>{{ $cuenta->subtipo ?? '-' }}</td>
                                                <td>{{ $cuenta->nivel }}</td>
                                                <td>{{ $cuenta->descripcion }}</td>
                                                <td>{{ $cuenta->estado ? 'Activo' : 'Inactivo' }}</td>
                                                <td class="action-links">
                                                    <a href="{{ route('cuentas_contables.edit', $cuenta->idCuentaContable) }}" class="link-edit">Editar</a>
                                                    <button type="button" class="link-delete"
                                                        onclick="document.getElementById('deleteModal-{{ $cuenta->idCuentaContable }}').showModal()">Eliminar</button>

                                                    <dialog id="deleteModal-{{ $cuenta->idCuentaContable }}">
                                                        <form method="POST" action="{{ route('cuentas_contables.destroy', $cuenta->idCuentaContable) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <p>¿Estás seguro de eliminar esta cuenta?</p>
                                                            <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                                                                <button type="button" onclick="this.closest('dialog').close()">Cancelar</button>
                                                                <button type="submit">Confirmar</button>
                                                            </div>
                                                        </form>
                                                    </dialog>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        @endforeach
                    </div>

                    {{-- Paginación --}}
                    <div class="pagination mt-2">
                        {{ $cuentas->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
