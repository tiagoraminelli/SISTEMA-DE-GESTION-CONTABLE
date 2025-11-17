<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight flex items-center">
            <svg class="w-6 h-6 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414a1 1 0 00-.707-.293H7a2 2 0 00-2 2v11m0 5l4-4m-4 4l4-4m-4 4h4"></path></svg>
            {{ __('Panel de Control Contable') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Card Mejorada: Total Clientes --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border-l-4 border-indigo-500 hover:shadow-2xl transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Clientes</p>
                            <p class="text-4xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $totalClientes }}</p>
                        </div>
                        <div class="p-3 bg-indigo-100 rounded-full dark:bg-indigo-900/50">
                            <svg class="w-8 h-8 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Card Mejorada: Total Asientos --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border-l-4 border-emerald-500 hover:shadow-2xl transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Asientos</p>
                            <p class="text-4xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $totalAsientos }}</p>
                        </div>
                        <div class="p-3 bg-emerald-100 rounded-full dark:bg-emerald-900/50">
                            <svg class="w-8 h-8 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 14h.01M12 17h.01M15 17h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Card Mejorada: Total Movimientos --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border-l-4 border-amber-500 hover:shadow-2xl transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Movimientos</p>
                            <p class="text-4xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $totalMovimientos }}</p>
                        </div>
                        <div class="p-3 bg-amber-100 rounded-full dark:bg-amber-900/50">
                            <svg class="w-8 h-8 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V9m0 3v2m0 3v1"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            ---

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">📊 Balance por Tipo de Cuenta</h3>
                    <div class="h-64">
                        <canvas id="balanceChart"></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">🧑‍💻 Clientes Activos / Inactivos</h3>
                    <div class="h-64 flex items-center justify-center">
                        <canvas id="clientesChart"></canvas>
                    </div>
                </div>
            </div>

            ---

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">📝 Últimos 10 Asientos Registrados</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider rounded-tl-lg">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider rounded-tr-lg">Descripción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($ultimosAsientos as $asiento)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $asiento->fecha->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $asiento->cliente->RazonSocial ?? 'Sin Cliente' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 truncate max-w-xs">{{ Str::limit($asiento->descripcion, 70) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No hay asientos recientes para mostrar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-right">
                    <a href="{{ route('asiento_contable.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-semibold text-sm">
                        Ver todos los Asientos &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
// Balance por tipo de cuenta
    const balanceCtx = document.getElementById('balanceChart').getContext('2d');
    new Chart(balanceCtx, {
        type: 'bar',
        data: {
            // Asegúrate de usar |raw para prevenir el doble escape de JSON
            labels: {!! json_encode(array_keys($balancePorTipo->toArray())) !!},
            datasets: [
                {
                    label: 'Debe',
                    data: {!! json_encode($balancePorTipo->map(fn($b) => $b['debe'])->values()) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                },
                {
                    label: 'Haber',
                    data: {!! json_encode($balancePorTipo->map(fn($b) => $b['haber'])->values()) !!},
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                }
            ]
        },
        // ...
    });

    // Clientes activos/inactivos
    const clientesCtx = document.getElementById('clientesChart').getContext('2d');
    new Chart(clientesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Activos', 'Inactivos'],
            datasets: [{
                // NO uses json_encode aquí, ya que son números simples
                data: [{{ $clientesActivos }}, {{ $clientesInactivos }}],
                backgroundColor: ['rgba(75, 192, 192, 0.7)', 'rgba(255, 99, 132, 0.7)'],
            }]
        },
        // ...
    });
    </script>
</x-app-layout>
