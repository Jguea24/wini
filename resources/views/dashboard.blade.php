<x-app-layout>
    @php($currentMonth = __('wini.months')[$selectedMonth - 1])

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ __('wini.dashboard') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">{{ __('wini.dashboard_subtitle') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <form class="flex gap-2">
                    <select name="mes" class="rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600">
                        @foreach(__('wini.months') as $index => $monthName)
                            <option value="{{ $index + 1 }}" @selected($selectedMonth === $index + 1)>{{ $monthName }}</option>
                        @endforeach
                    </select>
                    <input name="anio" type="number" value="{{ $selectedYear }}" min="2000" max="2100" class="w-24 rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600">
                    <button class="rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white">Filtrar</button>
                </form>
                <a href="{{ route('ventas.create') }}" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
                    {{ __('wini.new_sale') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">{{ __('wini.monthly_income', ['month' => $currentMonth]) }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">${{ number_format($totalIngresos, 2) }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">{{ __('wini.monthly_expenses', ['month' => $currentMonth]) }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">${{ number_format($totalGastos, 2) }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">{{ __('wini.monthly_profit', ['month' => $currentMonth]) }}</p>
                    <p class="mt-2 text-3xl font-bold {{ $gananciaNeta >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                        ${{ number_format($gananciaNeta, 2) }}
                    </p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">{{ __('wini.monthly_pounds_sold', ['month' => $currentMonth]) }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalLibrasVendidas, 2) }}</p>
                </div>
            </section>

            <section class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Cliente principal</p>
                    <p class="mt-2 text-xl font-bold text-gray-900">{{ $clientePrincipal?->cliente?->nombre_comercial ?? 'Sin datos' }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Mayor gasto de {{ $currentMonth }}</p>
                    <p class="mt-2 text-xl font-bold text-gray-900">${{ number_format($mayorGasto, 2) }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Precio promedio por libra</p>
                    <p class="mt-2 text-xl font-bold text-gray-900">${{ number_format($precioPromedioLibra, 2) }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Inversiones de {{ $currentMonth }}</p>
                    <p class="mt-2 text-xl font-bold text-emerald-800">${{ number_format($totalInversiones, 2) }}</p>
                </div>
            </section>

            <section class="mt-6 grid gap-6 lg:grid-cols-2">
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Ventas, gastos e inversiones</h3>
                            <p class="mt-1 text-sm text-gray-500">Totales acumulados de todos los registros.</p>
                        </div>
                    </div>
                    <div class="mt-4 grid gap-3 sm:grid-cols-4">
                        <div class="rounded-md bg-emerald-50 p-3">
                            <p class="text-xs font-medium uppercase text-emerald-700">Total ingresos</p>
                            <p class="mt-1 text-lg font-bold text-emerald-900">${{ number_format($totalIngresosGeneral, 2) }}</p>
                        </div>
                        <div class="rounded-md bg-amber-50 p-3">
                            <p class="text-xs font-medium uppercase text-amber-700">Total gastos</p>
                            <p class="mt-1 text-lg font-bold text-amber-900">${{ number_format($totalGastosGeneral, 2) }}</p>
                        </div>
                        <div class="rounded-md bg-sky-50 p-3">
                            <p class="text-xs font-medium uppercase text-sky-700">Total inversiones</p>
                            <p class="mt-1 text-lg font-bold text-sky-900">${{ number_format($totalInversionesGeneral, 2) }}</p>
                        </div>
                        <div class="rounded-md {{ $flujoGeneralDespuesInversion >= 0 ? 'bg-teal-50' : 'bg-red-50' }} p-3">
                            <p class="text-xs font-medium uppercase {{ $flujoGeneralDespuesInversion >= 0 ? 'text-teal-700' : 'text-red-700' }}">Ganancia total</p>
                            <p class="mt-1 text-lg font-bold {{ $flujoGeneralDespuesInversion >= 0 ? 'text-teal-900' : 'text-red-900' }}">${{ number_format($flujoGeneralDespuesInversion, 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-4 h-80">
                        <canvas id="ventasGastosChart"></canvas>
                    </div>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Tendencia mensual</h3>
                            <p class="mt-1 text-sm text-gray-500">Total vendido: {{ number_format($totalLibrasVendidasGeneral, 2) }} lb</p>
                        </div>
                    </div>
                    <div class="mt-4 h-80">
                        <canvas id="tendenciaChart"></canvas>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json(__('wini.months'));
        const ventasVsGastos = @json($ventasVsGastos);
        const tendenciaMensual = @json($tendenciaMensual);

        new Chart(document.getElementById('ventasGastosChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Ingresos', data: ventasVsGastos.map(row => row.ingresos), backgroundColor: '#047857' },
                    { label: 'Gastos', data: ventasVsGastos.map(row => row.gastos), backgroundColor: '#b45309' },
                    { label: 'Inversiones', data: ventasVsGastos.map(row => row.inversiones), backgroundColor: '#0369a1' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        new Chart(document.getElementById('tendenciaChart'), {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Ganancia despues de inversion',
                        data: tendenciaMensual.map(row => row.ganancia),
                        borderColor: '#0f766e',
                        backgroundColor: 'rgba(15, 118, 110, .12)',
                        fill: true,
                        tension: .3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</x-app-layout>
