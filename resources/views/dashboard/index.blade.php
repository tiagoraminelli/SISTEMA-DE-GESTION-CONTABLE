<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/clientes.index.css') }}">
    <x-slot name="header">
        <h2 class="page-header">PANEL DE CONTROL DEL SISTEMA CONTABLE</h2>
    </x-slot>

    <div class="py-12">
        <div class="container">

            {{-- FILTRO POR CLIENTE --}}
            <div class="card">
                <div class="card-content">
                    <form method="GET" action="{{ url('dashboard') }}" class="filter-form">
                        <label for="cliente_id">Filtrar por Cliente:</label>
                        <select name="cliente_id" id="cliente_id" class="filter-select" onchange="this.form.submit()">
                            <option value="">-- Todos --</option>
                            @foreach(\App\Models\Cliente::all() as $cliente)
                                <option value="{{ $cliente->idCliente }}"
                                    {{ isset($clienteId) && $clienteId == $cliente->idCliente ? 'selected' : '' }}>
                                    {{ $cliente->RazonSocial }}
                                </option>
                            @endforeach
                        </select>
                        @if(isset($clienteId))
                            <a href="{{ url('dashboard') }}" class="reset-button">Limpiar</a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- MÉTRICAS GENERALES --}}
            <div class="card mt-4">
                <div class="card-content">
                    <h3>Métricas Generales</h3>
                    <ul>
                        <li>Total Clientes: {{ $totalClientes }}</li>
                        <li>Clientes Activos: {{ $clientesActivos }}</li>
                        <li>Clientes Inactivos: {{ $clientesInactivos }}</li>
                        <li>Total Asientos: {{ $totalAsientos }}</li>
                        <li>Total Movimientos: {{ $totalMovimientos }}</li>
                        <li>Resultado del Ejercicio: {{ $resultadoEjercicio }}</li>
                    </ul>
                </div>
            </div>

            {{-- BALANCE POR TIPO DE CUENTA --}}
            <div class="card mt-4">
                <div class="card-content">
                    <h3>Balance por Tipo de Cuenta</h3>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Total Debe</th>
                                    <th>Total Haber</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($balancePorTipo as $tipo => $balance)
                                    <tr>
                                        <td>{{ $tipo }}</td>
                                        <td>{{ $balance->total_debe }}</td>
                                        <td>{{ $balance->total_haber }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="empty-state">No hay datos de balance.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- BALANCE GENERAL RESUMIDO --}}
            <div class="card mt-4">
                <div class="card-content">
                    <h3>Balance General Resumido</h3>
                    <ul>
                        <li>Activo: {{ $activo }}</li>
                        <li>Pasivo: {{ $pasivo }}</li>
                        <li>Patrimonio: {{ $patrimonio }}</li>
                    </ul>
                </div>
            </div>

            {{-- TOP CUENTAS MÁS USADAS --}}
            <div class="card mt-4">
                <div class="card-content">
                    <h3>Top 5 Cuentas Más Usadas</h3>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID Cuenta</th>
                                    <th>Usos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCuentas as $cuenta)
                                    <tr>
                                        <td>{{ $cuenta->CuentaContable_id }}</td>
                                        <td>{{ $cuenta->usos }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="empty-state">No hay datos de cuentas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ÚLTIMOS ASIENTOS --}}
            <div class="card mt-4">
                <div class="card-content">
                    <h3>Últimos Asientos</h3>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ultimosAsientos as $asiento)
                                    <tr>
                                        <td>{{ $asiento->fecha }}</td>
                                        <td>{{ $asiento->cliente->RazonSocial ?? 'Sin Cliente' }}</td>
                                        <td>{{ $asiento->descripcion }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="empty-state">No hay asientos recientes.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
