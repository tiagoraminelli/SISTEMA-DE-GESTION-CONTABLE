<x-app-layout>
    {{-- Estilos Tailwind CSS para mejorar la presentación --}}
    <link rel="stylesheet" href="{{ asset('css/clientes.show.css') }}">


    <x-slot name="header">
        <h2 class="page-header">{{ __('Detalle del Cliente') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="container-sm">
            <div class="card">
                <div class="card-content">
                    <h3 class="detail-title">{{ $cliente->RazonSocial }}</h3>

                    {{-- Información general del cliente --}}
                    <div class="detail-grid">
                        <div class="detail-item"><strong>CUIT:</strong> {{ $cliente->CUIT ?? '-' }}</div>
                        <div class="detail-item"><strong>Tipo Documento:</strong> {{ $cliente->TipoDocumento ?? '-' }}
                        </div>
                        <div class="detail-item"><strong>Nro Documento:</strong> {{ $cliente->NroDocumento ?? '-' }}
                        </div>
                        <div class="detail-item"><strong>Condición IVA:</strong> {{ $cliente->CondicionIVA ?? '-' }}
                        </div>
                        <div class="detail-item"><strong>Nro Ingresos Brutos:</strong>
                            {{ $cliente->NroIngBrutos ?? '-' }}</div>
                        <div class="detail-item"><strong>Condición Ingresos Brutos:</strong>
                            {{ $cliente->CondicionIngBrutos ?? '-' }}</div>
                        <div class="detail-item"><strong>Responsabilidad Social:</strong>
                            {{ $cliente->ResponsabilidadSocial ?? '-' }}</div>
                        <div class="detail-item"><strong>Límite de Crédito:</strong>
                            ${{ number_format($cliente->LimiteCredito, 2) }}</div>
                        <div class="detail-item"><strong>Activo:</strong>
                            <span class="{{ $cliente->Activo ? 'balance-ok' : 'balance-fail' }}">
                                {{ $cliente->Activo ? 'Sí' : 'No' }}
                            </span>
                        </div>
                        <div class="detail-item"><strong>Email:</strong> {{ $cliente->Email ?? '-' }}</div>
                        <div class="detail-item"><strong>Teléfono:</strong> {{ $cliente->Telefono ?? '-' }}</div>
                        <div class="detail-item"><strong>Domicilio Fiscal:</strong>
                            {{ $cliente->DomicilioFiscal ?? '-' }}</div>
                        <div class="detail-item"><strong>Localidad:</strong> {{ $cliente->Localidad ?? '-' }}</div>
                        <div class="detail-item"><strong>Provincia:</strong> {{ $cliente->Provincia ?? '-' }}</div>
                        <div class="detail-item"><strong>Código Postal:</strong> {{ $cliente->CodigoPostal ?? '-' }}
                        </div>
                        <div class="detail-item"><strong>País:</strong> {{ $cliente->Pais ?? '-' }}</div>
                        <div class="detail-item full-width"><strong>Observaciones:</strong>
                            <p class="mt-1 text-gray-600">{{ $cliente->Observaciones ?? '-' }}</p>
                        </div>
                        <div class="detail-item full-width"><strong>Fecha Alta:</strong>
                            {{ $cliente->FechaAlta ? $cliente->FechaAlta->format('d/m/Y') : '-' }}</div>
                    </div>

                    <div class="mb-6 flex gap-2 m-2">
                        <!-- Botón Estado de Situación Financiera -->
                        <a href="{{ route('estado_financiero.index', ['cliente_id' => $cliente->idCliente]) }}"
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-black bg-blue-600 hover:bg-blue-700">
                            Ver Estado de Situación Financiera
                        </a>
                    </div>




                    {{-- --- FILTRO DE FECHAS --- --}}
                    <form method="GET" action="{{ route('clientes.show', $cliente->idCliente) }}"
                        class="mb-6 flex flex-wrap gap-4 items-end">
                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha
                                Inicio:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio"
                                value="{{ request('fecha_inicio') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha Fin:</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" value="{{ request('fecha_fin') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Filtrar
                            </button>
                        </div>
                        <div>
                            <a href="{{ route('clientes.show', $cliente->idCliente) }}"
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Limpiar
                            </a>
                        </div>
                    </form>

                    @php
                        $fechaInicio = request('fecha_inicio');
                        $fechaFin = request('fecha_fin');

                        // Filtrar los asientos según fechas
                        $asientosFiltrados = $cliente->asientosContables;
                        if ($fechaInicio) {
                            $asientosFiltrados = $asientosFiltrados->filter(fn($a) => $a->fecha >= $fechaInicio);
                        }
                        if ($fechaFin) {
                            $asientosFiltrados = $asientosFiltrados->filter(fn($a) => $a->fecha <= $fechaFin);
                        }

                        // Calcular Mayor contable filtrado
                        $mayorContableFiltrado = [];
                        $totalGeneralDebe = 0;
                        $totalGeneralHaber = 0;

                        foreach ($asientosFiltrados as $asiento) {
                            foreach ($asiento->movimientos as $movimiento) {
                                $codigo = $movimiento->cuentaContable->codigo ?? '0000-E';
                                $nombre = $movimiento->cuentaContable->nombre ?? 'Cuenta Eliminada/Error';
                                if (!isset($mayorContableFiltrado[$codigo])) {
                                    $mayorContableFiltrado[$codigo] = [
                                        'codigo' => $codigo,
                                        'nombre' => $nombre,
                                        'debe' => 0,
                                        'haber' => 0,
                                    ];
                                }
                                $mayorContableFiltrado[$codigo]['debe'] += $movimiento->debe;
                                $mayorContableFiltrado[$codigo]['haber'] += $movimiento->haber;
                            }
                        }

                        foreach ($mayorContableFiltrado as $cuenta) {
                            $totalGeneralDebe += $cuenta['debe'];
                            $totalGeneralHaber += $cuenta['haber'];
                        }

                        ksort($mayorContableFiltrado);
                    @endphp

                    {{-- --- CUENTAS T FILTRADAS --- --}}
                    <h3 class="mt-8 detail-title">Mayor de Cuentas (Filtrado por Fechas)</h3>
                    @if (empty($mayorContableFiltrado))
                        <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded-md mt-4">
                            <p>No hay movimientos contables registrados en el rango de fechas seleccionado.</p>
                        </div>
                    @else
                        <div class="t-account-grid m-2 mb-4">
                            @foreach ($mayorContableFiltrado as $cuenta)
                                @php
                                    $saldo = $cuenta['debe'] - $cuenta['haber'];
                                    $tipoSaldo = $saldo >= 0 ? 'Deudor' : 'Acreedor';
                                    $claseSaldo = $saldo >= 0 ? 'saldo-deudor' : 'saldo-acreedor';
                                @endphp
                                <div class="t-account-card">
                                    <div class="t-account-header">
                                        <div class="t-account-code">{{ $cuenta['codigo'] }}</div>
                                        <div class="text-right">
                                            <div class="t-account-balance {{ $claseSaldo }}">
                                                ${{ number_format(abs($saldo), 2) }}</div>
                                            <div class="text-xs text-gray-500">Saldo: <strong
                                                    class="{{ $claseSaldo }}">{{ $tipoSaldo }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="t-account-name">{{ $cuenta['nombre'] }}</div>
                                    <div class="t-account-body">
                                        <div class="w-1/2 pr-2">
                                            <strong class="text-sm">Débito (Debe)</strong>
                                            <div class="text-lg {{ $saldo >= 0 ? 'saldo-deudor' : 'text-gray-700' }}">
                                                ${{ number_format($cuenta['debe'], 2) }}</div>
                                        </div>
                                        <div class="w-1/2 pl-2 text-right">
                                            <strong class="text-sm">Crédito (Haber)</strong>
                                            <div class="text-lg {{ $saldo < 0 ? 'saldo-acreedor' : 'text-gray-700' }}">
                                                ${{ number_format($cuenta['haber'], 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div
                            class="mt-8 p-4 mb-4 bg-gray-100 border border-gray-300 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center text-sm gap-4">
                            <div class="text-base font-bold text-gray-800 mb-2 sm:mb-0">TOTAL GENERAL DE MOVIMIENTOS:
                            </div>
                            <div class="flex flex-wrap gap-4 font-semibold">
                                <span class="text-gray-600">Total Debe: <strong
                                        class="text-gray-900">${{ number_format($totalGeneralDebe, 2) }}</strong></span>
                                <span class="text-gray-600">Total Haber: <strong
                                        class="text-gray-900">${{ number_format($totalGeneralHaber, 2) }}</strong></span>
                                @php
                                    $balanceGeneral = $totalGeneralDebe - $totalGeneralHaber;
                                    $balanceClase = abs($balanceGeneral) < 0.01 ? 'balance-ok' : 'balance-fail';
                                @endphp
                                <span class="text-base font-bold {{ $balanceClase }}">Balance Asientos:
                                    {{ abs($balanceGeneral) < 0.01 ? 'Cerrado' : 'Desbalanceado' }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- --- ASIENTOS CONTABLES FILTRADOS --- --}}
                    <h3 class="mt-12 detail-title">Detalle de Asientos Individuales (Filtrado por Fechas)</h3>

                    @php $asientos = $asientosFiltrados ?? collect(); @endphp

                    @if ($asientos->isEmpty())
                        <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded-md mt-4">
                            <p>No hay asientos contables en el rango de fechas seleccionado.</p>
                        </div>
                    @else
                        @foreach ($asientos as $asiento)
                            @if (!$loop->first)
                                <div class="asiento-separator"></div>
                            @endif
                            <div
                                class="asiento-header-container flex flex-col sm:flex-row sm:justify-between sm:items-center mb-2">
                                <h4 class="asiento-header">Asiento #{{ $asiento->idAsiento }} (Fecha:
                                    {{ \Carbon\Carbon::parse($asiento->fecha)->format('d/m/Y') }})</h4>
                                <div class="asiento-actions mt-2 sm:mt-0 flex gap-2">
                                    <a href="{{ route('asiento_contable.edit', $asiento->idAsiento) }}"
                                        class="px-3 py-1 bg-gray-600 text-gray-100 rounded hover:bg-gray-700 text-sm">Ver
                                        /
                                        Editar</a>
                                    <form action="{{ route('asiento_contable.destroy', $asiento->idAsiento) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este asiento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">Descripción:
                                <em>{{ $asiento->descripcion ?? 'N/A' }}</em>
                            </p>

                            <div class="table-responsive">
                                <table class="table-contable">
                                    <thead>
                                        <tr>
                                            <th>ID Mov.</th>
                                            <th>Cuenta</th>
                                            <th>Código</th>
                                            <th class="text-end">Debe</th>
                                            <th class="text-end">Haber</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalDebe = 0;
                                            $totalHaber = 0;
                                        @endphp
                                        @foreach ($asiento->movimientos as $movimiento)
                                            <tr>
                                                <td>{{ $movimiento->idMovimiento }}</td>
                                                <td
                                                    style="padding-left: {{ $movimiento->haber > 0 ? '2rem' : '1rem' }}">
                                                    {{ $movimiento->cuentaContable->nombre ?? 'Cuenta Eliminada' }}
                                                </td>
                                                <td>{{ $movimiento->cuentaContable->codigo ?? '-' }}</td>
                                                <td class="text-end">
                                                    {{ $movimiento->debe > 0 ? '$' . number_format($movimiento->debe, 2) : '-' }}
                                                </td>
                                                <td class="text-end">
                                                    {{ $movimiento->haber > 0 ? '$' . number_format($movimiento->haber, 2) : '-' }}
                                                </td>
                                                @php
                                                    $totalDebe += $movimiento->debe;
                                                    $totalHaber += $movimiento->haber;
                                                @endphp
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        @php
                                            $balance = $totalDebe - $totalHaber;
                                            $claseBalance = $balance == 0 ? 'balance-ok' : 'balance-fail';
                                        @endphp
                                        <tr>
                                            <td colspan="3" class="text-end">Totales:</td>
                                            <td class="text-end">${{ number_format($totalDebe, 2) }}</td>
                                            <td class="text-end">${{ number_format($totalHaber, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="{{ $claseBalance }}">Balance del Asiento:
                                                {{ $balance == 0 ? 'Cerrado' : 'Desbalanceado' }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endforeach

                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
