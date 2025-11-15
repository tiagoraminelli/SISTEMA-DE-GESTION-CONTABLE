<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/clientes.index.css') }}">
    <x-slot name="header">
        <h2 class="page-header">{{ __('Clientes') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="container">

            {{-- Modal nativo con tu estilo CSS --}}
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

                            // Cerrar al presionar el botón
                            closeBtn.addEventListener('click', () => dialog.close());

                            // También cerrar al hacer clic fuera
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

                            // (Opcional) cierre automático después de 3 segundos
                            setTimeout(() => dialog.close(), 3000);
                        } else {
                            console.warn("Tu navegador no soporta <dialog>");
                        }
                    });
                </script>
            @endif



            <div class="card">
                <div class="card-content">

                    <div class="controls-header">
                        <a href="{{ route('clientes.create') }}" class="btn-create">Crear Cliente</a>

                        <form method="GET" action="{{ route('clientes.index') }}" class="filter-form">
                            <input type="text" name="search" placeholder="Buscar (Razón/CUIT)" class="filter-input"
                                value="{{ request('search') }}">
                            <select name="activo" class="filter-select">
                                <option value="">Estado (Todos)</option>
                                <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivo
                                </option>
                            </select>
                            <select name="iva" class="filter-select">
                                <option value="">Condición IVA (Todas)</option>
                                <option value="RI" {{ request('iva') === 'RI' ? 'selected' : '' }}>Responsable
                                    Inscripto</option>
                                <option value="CF" {{ request('iva') === 'CF' ? 'selected' : '' }}>Consumidor Final
                                </option>
                                <option value="EX" {{ request('iva') === 'EX' ? 'selected' : '' }}>Exento</option>
                                <option value="MT" {{ request('iva') === 'MT' ? 'selected' : '' }}>Monotributista
                                </option>
                            </select>
                            <button type="submit" class="filter-button">Filtrar</button>
                            @if (request()->has('search') || request()->has('activo') || request()->has('iva'))
                                <a href="{{ route('clientes.index') }}" class="reset-button">Limpiar</a>
                            @endif
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Razon Social</th>
                                    <th>CUIT/NIF</th>
                                    <th>Condicion I.V.A</th>
                                    <th>Email</th>
                                    <th>Cód.Postal</th>
                                    <th>Teléfono</th>
                                    <th>Activo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clientes as $cliente)
                                    <tr>
                                        <td>{{ $cliente->idCliente }}</td>
                                        <td>{{ $cliente->RazonSocial }}</td>
                                        <td>{{ $cliente->CUIT }}</td>
                                        <td>{{ $cliente->CondicionIVA }}</td>
                                        <td>{{ $cliente->Email }}</td>
                                        <td>{{ $cliente->CodigoPostal }}</td>
                                        <td>{{ $cliente->Telefono }}</td>
                                        <td>{{ $cliente->Activo ? 'Sí' : 'No' }}</td>
                                        <td class="action-links">

                                            <a href="{{ route('clientes.show', $cliente->idCliente) }}"
                                                class="link-view">Ver</a>
                                            <a href="{{ route('clientes.edit', $cliente->idCliente) }}"
                                                class="link-edit">Editar</a>

                                            {{-- Botón Eliminar con dialog --}}
                                            <button type="button" class="link-delete"
                                                onclick="document.getElementById('deleteModal-{{ $cliente->idCliente }}').showModal()">Eliminar</button>

                                            <dialog id="deleteModal-{{ $cliente->idCliente }}">
                                                <form method="POST"
                                                    action="{{ route('clientes.destroy', $cliente->idCliente) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <p>¿Estás seguro de eliminar este cliente?</p>
                                                    <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                                                        <button type="button"
                                                            onclick="this.closest('dialog').close()">Cancelar</button>
                                                        <button type="submit">Confirmar</button>
                                                    </div>
                                                </form>
                                            </dialog>

                                            {{-- Botón Restaurar con dialog --}}
                                            <button type="button" class="link-success"
                                                onclick="document.getElementById('restoreModal-{{ $cliente->idCliente }}').showModal()">Restaurar</button>

                                            <dialog id="restoreModal-{{ $cliente->idCliente }}">
                                                <form method="POST"
                                                    action="{{ route('clientes.restore', $cliente->idCliente) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <p>¿Estás seguro de restaurar este cliente?</p>
                                                    <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                                                        <button type="button"
                                                            onclick="this.closest('dialog').close()">Cancelar</button>
                                                        <button type="submit">Confirmar</button>
                                                    </div>
                                                </form>
                                            </dialog>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="empty-state">No hay clientes registrados o no se
                                            encontraron resultados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination mt-2">
                        {{ $clientes->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
