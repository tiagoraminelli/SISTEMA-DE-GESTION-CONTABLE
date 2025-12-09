<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100 mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">
            {{ __('Ecuación Fundamental Dinámica') }}
            @if($cliente) <span class="text-indigo-600 dark:text-indigo-400">— {{ $cliente->RazonSocial }}</span> @endif
        </h2>
    </x-slot>

    <div class="py-8">
        {{-- CONTENEDOR AJUSTADO: Ancho de 780px centrado --}}
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 780px;">

            <div class="flex flex-col w-full gap-8">

                {{-- FILTROS --}}
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Filtros de Período</h3>

                    <form method="GET" action="{{ route('estado_financiero.detalle') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                        {{-- Cliente --}}
                        <div class="md:col-span-4">
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente</label>
                            <select name="cliente_id" id="cliente_id"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
                                       focus:border-indigo-500 focus:ring-indigo-500 text-sm rounded-lg shadow-sm w-full">
                                <option value="">-- Seleccione Cliente --</option>
                                @foreach($clientes as $c)
                                    <option value="{{ $c->idCliente }}" {{ (isset($clienteId) && $clienteId == $c->idCliente) ? 'selected' : '' }}>
                                        {{ $c->RazonSocial }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Fecha Inicio --}}
                        <div class="md:col-span-1">
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Desde</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ $fechaInicio ?? '' }}"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
                                       focus:border-indigo-500 focus:ring-indigo-500 text-sm rounded-lg shadow-sm w-full">
                        </div>

                        {{-- Fecha Fin --}}
                        <div class="md:col-span-1">
                            <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hasta</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" value="{{ $fechaFin ?? '' }}"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
                                       focus:border-indigo-500 focus:ring-indigo-500 text-sm rounded-lg shadow-sm w-full">
                        </div>

                        {{-- Botón --}}
                        <div class="md:col-span-2 flex items-end">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-lg shadow-md
                                       text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2
                                       focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 transform hover:scale-[1.01]">
                                Aplicar Filtros
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Contenido principal: Tablas y Ecuación --}}
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 space-y-8 border border-gray-100 dark:border-gray-700">

                    {{-- DETALLE DE CUENTAS AGRUPADAS --}}
                    <div>
                        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100 border-b border-indigo-200 dark:border-indigo-700 pb-2">
                            Detalle de Cuentas T por Tipo
                        </h2>

                        @php
                            $ordenTipos = ['activo', 'pasivo', 'patrimonio neto', 'resultado positivo', 'resultado negativo'];
                            $titulosTipos = [
                                'activo' => 'ACTIVO (Bienes y Derechos)',
                                'pasivo' => 'PASIVO (Obligaciones)',
                                'patrimonio neto' => 'PATRIMONIO NETO (Capital y Reservas)',
                                'resultado positivo' => 'RESULTADO POSITIVO (Ingresos y Ganancias)',
                                'resultado negativo' => 'RESULTADO NEGATIVO (Costos y Pérdidas)'
                            ];
                        @endphp

                        @foreach($ordenTipos as $tipo)
                            @if(isset($cuentasAgrupadas[$tipo]) && count($cuentasAgrupadas[$tipo]) > 0)
                                <div class="mt-6">
                                    {{-- Encabezado de la Sección --}}
                                    <h3 class="text-lg font-bold mb-2 p-1 rounded-md text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/40">
                                        {{ $titulosTipos[$tipo] }}
                                    </h3>

                                    @php
                                        $ordenadas = collect($cuentasAgrupadas[$tipo])->sortByDesc(fn($c) => $c['saldo']);
                                        $totalDeudor = 0;
                                        $totalAcreedor = 0;
                                    @endphp

                                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                            <thead class="bg-gray-100 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-4 py-2 text-left font-bold text-gray-700 dark:text-gray-300 w-1/3">Cuenta</th>
                                                    <th class="px-4 py-2 text-right font-bold text-gray-700 dark:text-gray-300">Debe</th>
                                                    <th class="px-4 py-2 text-right font-bold text-gray-700 dark:text-gray-300">Haber</th>
                                                    <th class="px-4 py-2 text-right font-bold text-green-700 dark:text-green-400">Saldo D</th>
                                                    <th class="px-4 py-2 text-right font-bold text-red-600 dark:text-red-400">Saldo A</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                                @foreach($ordenadas as $cuenta)
                                                    {{-- Fila cebra con hover --}}
                                                    <tr class="odd:bg-gray-50 dark:odd:bg-gray-800/50 hover:bg-gray-200 dark:hover:bg-gray-700 transition duration-150">
                                                        <td class="px-4 py-2 font-medium whitespace-nowrap">{{ $cuenta['nombre'] }}</td>
                                                        <td class="px-4 py-2 text-right font-mono whitespace-nowrap">{{ number_format($cuenta['debe'], 2, ',', '.') }}</td>
                                                        <td class="px-4 py-2 text-right font-mono whitespace-nowrap">{{ number_format($cuenta['haber'], 2, ',', '.') }}</td>
                                                        <td class="px-4 py-2 text-right font-mono text-green-700 dark:text-green-500 whitespace-nowrap">
                                                            {{ $cuenta['saldo'] > 0 ? number_format($cuenta['saldo'], 2, ',', '.') : '' }}
                                                        </td>
                                                        <td class="px-4 py-2 text-right font-mono text-red-600 dark:text-red-500 whitespace-nowrap">
                                                            {{ $cuenta['saldo'] < 0 ? number_format(abs($cuenta['saldo']), 2, ',', '.') : '' }}
                                                        </td>
                                                    </tr>

                                                    @php
                                                        if($cuenta['saldo'] > 0) $totalDeudor += $cuenta['saldo'];
                                                        else $totalAcreedor += abs($cuenta['saldo']);
                                                    @endphp
                                                @endforeach

                                                <tr class="font-extrabold bg-indigo-200 dark:bg-indigo-900/60 border-t-2 border-indigo-400 dark:border-indigo-600">
                                                    <td class="px-4 py-2 whitespace-nowrap">SUBTOTAL:</td>
                                                    <td class="px-4 py-2 text-right"></td>
                                                    <td class="px-4 py-2 text-right"></td>
                                                    <td class="px-4 py-2 text-right text-green-800 dark:text-green-300 whitespace-nowrap">{{ number_format($totalDeudor, 2, ',', '.') }}</td>
                                                    <td class="px-4 py-2 text-right text-red-700 dark:text-red-300 whitespace-nowrap">{{ number_format($totalAcreedor, 2, ',', '.') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- ECUACIÓN DINÁMICA: Resumen Final --}}
                    <div class="mt-8 pt-6 border-t border-gray-300 dark:border-gray-700">
                        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100">Cierre: Ecuación Fundamental Dinámica</h2>

                        @php
                            $activoTotal = $totales['activo'] ?? 0;
                            $pasivoTotal = $totales['pasivo'] ?? 0;
                            $patrimonioTotal = $totales['patrimonio neto'] ?? 0;
                            $resultadoPositivo = $totales['resultado positivo'] ?? 0;
                            $resultadoNegativo = $totales['resultado negativo'] ?? 0;

                            $resultadoEjercicio = ($resultadoPositivo - abs($resultadoNegativo));

                            $patrimonioDinamico = $patrimonioTotal + $resultadoEjercicio;
                            $ladoIzquierdo = $activoTotal;
                            $ladoDerecho = $pasivoTotal + $patrimonioDinamico;

                            $descuadreDinamico = $ladoIzquierdo - $ladoDerecho;
                            $tolerancia = 0.01;
                            $equilibrada = abs($descuadreDinamico) <= $tolerancia;
                            $claseEquilibrio = $equilibrada ? 'bg-green-600 border-green-700' : 'bg-red-600 border-red-700';
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Columna 1: Resumen de Totales --}}
                            <div class="space-y-2 text-sm sm:text-base bg-gray-50 dark:bg-gray-900/40 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-md">
                                <p class="font-bold text-lg mb-2 border-b pb-1 text-gray-800 dark:text-gray-200">Totales del Período</p>
                                <p><span class="font-semibold text-green-700 dark:text-green-400">ACTIVO:</span> ${{ number_format($activoTotal, 2, ',', '.') }}</p>
                                <p><span class="font-semibold text-red-700 dark:text-red-400">PASIVO:</span> ${{ number_format($pasivoTotal, 2, ',', '.') }}</p>
                                <p><span class="font-semibold">PATRIMONIO NETO:</span> ${{ number_format($patrimonioTotal, 2, ',', '.') }}</p>
                                <hr class="my-2 border-gray-300 dark:border-gray-700">
                                <p class="font-bold"><span class="text-indigo-600 dark:text-indigo-400">RESULTADO EJERCICIO:</span> ${{ number_format($resultadoEjercicio, 2, ',', '.') }}</p>
                            </div>

                            {{-- Columna 2: Ecuación y Balance --}}
                            <div class="md:col-span-1">
                                <div class="h-full flex flex-col justify-between">
                                    <div class="mt-4 p-5 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 border-2 border-indigo-400 dark:border-indigo-600 shadow-lg mb-4">
                                        <p class="gap-2 text-lg font-bold text-indigo-600 dark:text-indigo-400">Fórmula Contable</p>

                                        {{-- Fórmula --}}
                                        <p class="text-sm sm:text-lg font-mono mb-2 border-b border-indigo-300 dark:border-indigo-600 pb-2">
                                            Activo = Pasivo + Patrimonio Neto Dinámico
                                        </p>

                                        {{-- Valores --}}
                                        <p class="text-base sm:text-xl font-mono font-bold">
                                            {{ number_format($ladoIzquierdo, 2, ',', '.') }} = {{ number_format($ladoDerecho, 2, ',', '.') }}
                                        </p>
                                    </div>

                                    {{-- Indicador de Equilibrio --}}
                                    <div class="p-4 text-center text-white rounded-xl shadow-lg transition duration-300 {{ $claseEquilibrio }}">
                                        <p class="text-xl font-black">
                                            {{ $equilibrada ? 'BALANCE CUADRADO' : 'DESCUADRE DETECTADO' }}
                                        </p>
                                        <p class="text-xs font-semibold mt-1">
                                            Diferencia: ${{ number_format(abs($descuadreDinamico), 2, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 italic">
                            Nota: La Ecuación Fundamental Dinámica (Activo = Pasivo + Patrimonio Neto + Resultado) es clave para el Balance General.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- Select2 --}}
    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#cliente_id').select2({
                    placeholder: '-- Seleccione Cliente --',
                    width: '100%',
                    theme: 'classic',
                });
            });
        </script>
    @endpush

</x-app-layout>
