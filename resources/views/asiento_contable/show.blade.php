<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/clientes.index.css') }}">
    <x-slot name="header">
        <h2 class="page-header">{{ __('Detalle del Asiento Contable') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="container">

            {{-- Modal nativo de éxito --}}
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
                    <h3>Información General</h3>
                    <table class="data-table">
                        <tr>
                            <th>ID</th>
                            <td>{{ $asiento->idAsiento }}</td>
                        </tr>
                        <tr>
                            <th>Cliente</th>
                            <td>{{ $asiento->cliente->RazonSocial ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha</th>
                            <td>{{ $asiento->fecha->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <th>Descripción</th>
                            <td>{{ $asiento->descripcion }}</td>
                        </tr>
                    </table>

                    <h3 class="mt-6">Movimientos Contables</h3>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Cuenta Contable</th>
                                    <th>Variación Patrimonia</th>
                                    <th>Debe</th>
                                    <th>Haber</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($asiento->movimientos as $movimiento)
                                    <tr>
                                       <td>{{ $movimiento->cuentaContable->nombre ?? 'N/A' }}</td>
                                       <td>{{ $movimiento->cuentaContable->tipo ?? 'N/A' }}</td>
                                        <td>{{ number_format($movimiento->debe, 2) }}</td>
                                        <td>{{ number_format($movimiento->haber, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="empty-state">No hay movimientos registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('asiento_contable.index') }}" class="btn-create">Volver al listado</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
