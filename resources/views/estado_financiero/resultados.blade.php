<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header text-2xl sm:text-3xl font-semibold text-gray-800 dark:text-gray-100 mb-6 border-b pb-2">
            {{ __('ESTADO DE RESULTADOS -') }} {{ $cliente->RazonSocial ?? 'General' }}
        </h2>
    </x-slot>

    {{-- El contenedor principal 'py-6' mantiene el centrado vertical --}}
    <div class="py-6">
        {{-- ESTE CONTENEDOR ES EL QUE CONTROLA EL CENTRADO HORIZONTAL --}}
        {{-- max-w-6xl: Define el ancho máximo (centrado). mx-auto: Centra el bloque. --}}
        <div class="max-w-6xl mx-center sm:px-4 lg:px-6" style="display: flex; justify-content: center;">

            {{-- Contenedor FLEX para las dos columnas --}}
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- Columna Izquierda: FORMULARIO DE FILTRO (1/3) --}}
                <div class="lg:w-1/3 p-6 bg-white dark:bg-gray-800 shadow-xl sm:rounded-xl">
                    <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Filtros</h3>

                    <form method="GET" action="{{ url()->current() }}"
                        class="grid grid-cols-1 gap-4 items-end">

                        {{-- Cliente --}}
                        <div>
                            <label for="cliente_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente:</label>
                            <select name="cliente_id" id="cliente_id"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm rounded-lg shadow-sm w-full py-2">
                                <option value="">-- Seleccione Cliente --</option>
                                @foreach ($clientes as $c)
                                    <option value="{{ $c->idCliente }}"
                                        {{ isset($clienteId) && $clienteId == $c->idCliente ? 'selected' : '' }}>
                                        {{ $c->RazonSocial }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Fecha Inicio --}}
                        <div>
                            <label for="fecha_inicio"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Desde:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ $fechaInicio ?? '' }}"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm rounded-lg shadow-sm w-full py-2">
                        </div>

                        {{-- Fecha Fin --}}
                        <div>
                            <label for="fecha_fin"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasta:</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" value="{{ $fechaFin ?? '' }}"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm rounded-lg shadow-sm w-full py-2">
                        </div>

                        {{-- Botón --}}
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg shadow-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Aplicar Filtros
                            </button>
                        </div>

                    </form>
                </div>

                {{-- Columna Derecha: TABLA DE ESTADO DE RESULTADOS (2/3) --}}
                <div class="lg:w-2/3 bg-white dark:bg-gray-800 shadow-xl sm:rounded-xl p-6">
                    <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Resultado Detallado</h3>

                    <table class="w-full text-sm sm:text-base text-gray-900 dark:text-gray-200">
                        <tbody>
                            {{-- INGRESOS --}}
                            <tr>
                                <td colspan="2" class="text-lg sm:text-xl font-bold py-2 border-b">INGRESOS</td>
                            </tr>
                            @foreach ($ingresos as $item)
                                <tr>
                                    <td class="pl-4 py-1">{{ $item->nombre }}</td>
                                    <td class="text-right pr-4 py-1">{{ number_format($item->saldo, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="font-bold bg-indigo-100 dark:bg-indigo-900/50">
                                <td class="pl-3 py-2">TOTAL INGRESOS</td>
                                <td class="text-right pr-4 py-2">${{ number_format($totalIngresos, 2, ',', '.') }}</td>
                            </tr>

                            {{-- EGRESOS --}}
                            <tr>
                                <td colspan="2" class="text-lg sm:text-xl font-bold py-2 border-b mt-4">EGRESOS</td>
                            </tr>
                            @foreach ($egresos as $item)
                                <tr>
                                    <td class="pl-4 py-1">{{ $item->nombre }}</td>
                                    <td class="text-right pr-4 py-1">{{ number_format($item->saldo, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="font-bold bg-red-100 dark:bg-red-900/50">
                                <td class="pl-3 py-2">TOTAL EGRESOS</td>
                                <td class="text-right pr-4 py-2">${{ number_format($totalEgresos, 2, ',', '.') }}</td>
                            </tr>

                            {{-- RESULTADO DEL EJERCICIO --}}
                            <tr class="text-base sm:text-lg font-extrabold bg-green-100 dark:bg-green-900/50">
                                <td class="pl-3 py-3 border-t">RESULTADO DEL EJERCICIO</td>
                                <td class="text-right pr-4 py-3 border-t border-b-4 border-double">
                                    ${{ number_format($resultadoEjercicio, 2, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
