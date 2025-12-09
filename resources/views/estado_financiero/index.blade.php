<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4 border-b pb-2">
            {{ __('ESTADO DE SITUACIÓN FINANCIERA -') }} {{ $cliente->RazonSocial ?? 'General' }}
        </h2>
    </x-slot>

    <div class="py-6">
        {{-- Contenedor más pequeño: max-w-3xl --}}
        <div class="max-w-1xl mx-auto sm:px-6 lg:px-8">

            {{-- FORMULARIO DE FILTRO POR CLIENTE Y PERIODO --}}
            <div class="mb-6 p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg max-sm:max-w-3xl mx-auto">
                <form method="GET" action="{{ url()->current() }}"
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">

                    {{-- Filtro Cliente --}}
                    <div>
                        <label for="cliente_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente:</label>
                        <select name="cliente_id" id="cliente_id"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            <option value="">-- Seleccione un Cliente --</option>
                            @foreach ($clientes as $c)
                                <option value="{{ $c->idCliente }}"
                                    {{ isset($clienteId) && $clienteId == $c->idCliente ? 'selected' : '' }}>
                                    {{ $c->RazonSocial }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro Fecha Inicio --}}
                    <div>
                        <label for="fecha_inicio"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Desde:</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ $fechaInicio ?? '' }}"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                    </div>

                    {{-- Filtro Fecha Fin --}}
                    <div>
                        <label for="fecha_fin"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasta:</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" value="{{ $fechaFin ?? '' }}"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                    </div>

                    {{-- Botón de Enviar --}}
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Aplicar Filtros
                        </button>
                    </div>

                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg p-4">

                {{-- Contenedor principal del Estado de Situación Financiera --}}
                <table class="w-full text-sm text-gray-900 dark:text-gray-200" style="border-collapse: collapse;">

                    {{-- ----------------------------- ACTIVO ----------------------------- --}}
                    <tbody>
                        <tr>
                            <td colspan="2"
                                class="text-lg font-bold py-2 border-b border-gray-300 dark:border-gray-600">ACTIVO</td>
                        </tr>

                        {{-- ACTIVO CORRIENTE --}}
                        <tr class="font-bold bg-gray-50 dark:bg-gray-700">
                            <td class="pl-2 py-1">Activo Corriente</td>
                            <td></td>
                        </tr>
                        @foreach ($activoCorriente as $cuenta)
                            <tr>
                                <td class="pl-6 py-0.5">{{ $cuenta->nombre }}</td>
                                <td class="text-right pr-2">{{ number_format($cuenta->saldo, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        {{-- ACTIVO NO CORRIENTE --}}
                        <tr class="font-bold bg-gray-50 dark:bg-gray-700 mt-2">
                            <td class="pl-2 py-1">Activo No Corriente</td>
                            <td></td>
                        </tr>
                        @foreach ($activoNoCorriente as $cuenta)
                            <tr>
                                <td class="pl-6 py-0.5">{{ $cuenta->nombre }}</td>
                                <td class="text-right pr-2">{{ number_format($cuenta->saldo, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        {{-- TOTAL ACTIVO --}}
                        <tr class="text-md font-extrabold bg-indigo-100 dark:bg-indigo-900/50">
                            <td class="pl-2 py-1.5 border-t border-gray-400 dark:border-gray-500">TOTAL ACTIVO</td>
                            <td
                                class="text-right pr-2 py-1.5 border-t border-gray-400 dark:border-gray-500 border-b-4 border-double border-gray-900 dark:border-gray-100">
                                ${{ number_format($totalActivo, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>

                    {{-- ----------------------------- PASIVO Y PATRIMONIO NETO ----------------------------- --}}
                    <tbody>
                        <tr>
                            <td colspan="2"
                                class="text-lg font-bold py-4 border-t border-gray-300 dark:border-gray-600">PASIVO Y
                                PATRIMONIO NETO</td>
                        </tr>

                        {{-- PASIVO CORRIENTE --}}
                        <tr class="font-bold bg-gray-50 dark:bg-gray-700">
                            <td class="pl-2 py-1">Pasivo Corriente</td>
                            <td></td>
                        </tr>
                        @foreach ($pasivoCorriente as $cuenta)
                            <tr>
                                <td class="pl-6 py-0.5">{{ $cuenta->nombre }}</td>
                                <td class="text-right pr-2">{{ number_format($cuenta->saldo, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        {{-- PASIVO NO CORRIENTE --}}
                        <tr class="font-bold bg-gray-50 dark:bg-gray-700 mt-2">
                            <td class="pl-2 py-1">Pasivo No Corriente</td>
                            <td></td>
                        </tr>
                        @foreach ($pasivoNoCorriente as $cuenta)
                            <tr>
                                <td class="pl-6 py-0.5">{{ $cuenta->nombre }}</td>
                                <td class="text-right pr-2">{{ number_format($cuenta->saldo, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        <tr class="font-bold border-t border-gray-300 dark:border-gray-600">
                            <td class="text-right pr-2 py-1">TOTAL PASIVO</td>
                            <td
                                class="text-right pr-2 border-b-2 border-double border-indigo-500 dark:border-indigo-400">
                                {{ number_format($totalPasivo, 2, ',', '.') }}
                            </td>
                        </tr>

                        {{-- PATRIMONIO NETO --}}
                        <tr>
                            <td colspan="2"
                                class="text-md font-bold py-2 border-t border-gray-300 dark:border-gray-600">PATRIMONIO
                                NETO</td>
                        </tr>
                        @foreach ($patrimonio as $cuenta)
                            <tr>
                                <td class="pl-6 py-0.5">{{ $cuenta->nombre }}</td>
                                <td class="text-right pr-2">{{ number_format($cuenta->saldo, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        {{-- TOTAL PASIVO + PATRIMONIO NETO --}}
                        <tr class="text-md font-extrabold bg-green-100 dark:bg-green-900/50">
                            <td class="pl-2 py-1.5 border-t border-gray-400 dark:border-gray-500">TOTAL PASIVO +
                                PATRIMONIO NETO</td>
                            <td
                                class="text-right pr-2 py-1.5 border-t border-gray-400 dark:border-gray-500 border-b-4 border-double border-gray-900 dark:border-gray-100">
                                ${{ number_format($totalPasivo + $totalPatrimonio, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Verificación de la Ecuación Contable (opcional) --}}
                @php
                    $diferencia = abs($totalActivo - ($totalPasivo + $totalPatrimonio));
                @endphp
                @if ($diferencia > 0.01)
                    <div class="mt-4 p-3 text-xs bg-red-100 dark:bg-red-900/50 text-white dark:text-red-300 rounded">
                        <p class="font-bold">🚨 ¡Descuadre Contable!</p>
                        <p>Diferencia: ${{ number_format($diferencia, 2) }}</p>
                    </div>
                @endif




            </div>
        </div>
    </div>
</x-app-layout>
