<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">
            ESTADO DE RESULTADOS
            <span class="text-gray-600 dark:text-gray-400 font-normal">
                — {{ $cliente->RazonSocial ?? 'General' }}
            </span>
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="mx-auto px-4" style="max-width: 1200px;">

            <div class="grid grid-cols-12 gap-4">

                {{-- RESULTADOS --}}
                <div class="col-span-12 lg:col-span-8">
                    <div class="bg-white border border-gray-200 rounded-md p-4">

                                        {{-- FILTROS --}}
                <div class="col-span-12 lg:col-span-4">
                    <div class="bg-white border border-gray-200 rounded-md p-4 sticky top-4">

                        <form method="GET" action="{{ url()->current() }}" class="space-y-3">

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Cliente
                                </label>
                                <select name="cliente_id" id="cliente_id"
                                    class="w-full rounded border-gray-300 text-sm focus:ring-black focus:border-black">
                                    <option value="">Seleccionar</option>
                                    @foreach ($clientes as $c)
                                        <option value="{{ $c->idCliente }}"
                                            {{ isset($clienteId) && $clienteId == $c->idCliente ? 'selected' : '' }}>
                                            {{ $c->RazonSocial }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Desde
                                </label>
                                <input type="date" name="fecha_inicio"
                                    value="{{ $fechaInicio ?? '' }}"
                                    class="w-full rounded border-gray-300 text-sm focus:ring-black focus:border-black">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Hasta
                                </label>
                                <input type="date" name="fecha_fin"
                                    value="{{ $fechaFin ?? '' }}"
                                    class="w-full rounded border-gray-300 text-sm focus:ring-black focus:border-black">
                            </div>

                            <button type="submit"
                                class="block mt-2 px-3 py-2 rounded bg-black text-black text-sm font-medium hover:bg-gray-800 transition">
                                Aplicar filtros
                            </button>

                        </form>
                    </div>
                </div>


                        <table class="w-full text-sm text-gray-800 mt-2">
                            <tbody>

                                {{-- INGRESOS --}}
                                <tr>
                                    <td colspan="2"
                                        class="font-semibold text-gray-900 border-b border-gray-200 pb-1">
                                        INGRESOS
                                    </td>
                                </tr>

                                @foreach ($ingresos as $item)
                                    <tr class="text-gray-600">
                                        <td class="pl-4 py-0.5">{{ $item->nombre }}</td>
                                        <td class="text-right py-0.5">
                                            {{ number_format($item->saldo, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach

                                <tr class="font-semibold text-gray-900 border-t border-gray-300">
                                    <td class="pt-2">TOTAL INGRESOS</td>
                                    <td class="text-right pt-2">
                                        ${{ number_format($totalIngresos, 2, ',', '.') }}
                                    </td>
                                </tr>

                                {{-- EGRESOS --}}
                                <tr>
                                    <td colspan="2"
                                        class="font-semibold text-gray-900 border-t border-gray-300 pt-4 pb-1">
                                        EGRESOS
                                    </td>
                                </tr>

                                @foreach ($egresos as $item)
                                    <tr class="text-gray-600">
                                        <td class="pl-4 py-0.5">{{ $item->nombre }}</td>
                                        <td class="text-right py-0.5">
                                            {{ number_format($item->saldo, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach

                                <tr class="font-semibold text-gray-900 border-t border-gray-300">
                                    <td class="pt-2">TOTAL EGRESOS</td>
                                    <td class="text-right pt-2">
                                        ${{ number_format($totalEgresos, 2, ',', '.') }}
                                    </td>
                                </tr>

                                {{-- RESULTADO --}}
                                <tr class="font-semibold text-gray-900 border-t border-gray-400">
                                    <td class="pt-3">RESULTADO DEL EJERCICIO</td>
                                    <td class="text-right pt-3">
                                        ${{ number_format($resultadoEjercicio, 2, ',', '.') }}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>
