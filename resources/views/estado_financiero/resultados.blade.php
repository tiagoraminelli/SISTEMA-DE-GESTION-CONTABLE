<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4 border-b pb-2">
            {{ __('ESTADO DE RESULTADOS -') }} {{ $cliente->RazonSocial ?? 'General' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

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

            {{-- TABLA DE ESTADO DE RESULTADOS --}}
            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg p-4">
                <table class="w-full text-sm text-gray-900 dark:text-gray-200">
                    <tbody>
                        <tr><td colspan="2" class="text-lg font-bold py-2 border-b">INGRESOS</td></tr>
                        @foreach ($ingresos as $item)
                            <tr>
                                <td class="pl-6 py-0.5">{{ $item->nombre }}</td>
                                <td class="text-right pr-2">{{ number_format($item->saldo,2,',','.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="font-bold bg-indigo-100 dark:bg-indigo-900/50">
                            <td class="pl-2 py-1.5">TOTAL INGRESOS</td>
                            <td class="text-right pr-2 py-1.5">${{ number_format($totalIngresos,2,',','.') }}</td>
                        </tr>

                        <tr><td colspan="2" class="text-lg font-bold py-2 border-b mt-4">EGRESOS</td></tr>
                        @foreach ($egresos as $item)
                            <tr>
                                <td class="pl-6 py-0.5">{{ $item->nombre }}</td>
                                <td class="text-right pr-2">{{ number_format($item->saldo,2,',','.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="font-bold bg-red-100 dark:bg-red-900/50">
                            <td class="pl-2 py-1.5">TOTAL EGRESOS</td>
                            <td class="text-right pr-2 py-1.5">${{ number_format($totalEgresos,2,',','.') }}</td>
                        </tr>

                        <tr class="text-md font-extrabold bg-green-100 dark:bg-green-900/50">
                            <td class="pl-2 py-1.5 border-t">RESULTADO DEL EJERCICIO</td>
                            <td class="text-right pr-2 py-1.5 border-t border-b-4 border-double">
                                ${{ number_format($resultadoEjercicio,2,',','.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
