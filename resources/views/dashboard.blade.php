<x-app-layout>
    @php
        $selectedMonth = (int) ($selectedMonth ?? now()->month);
        $selectedYear = (int) ($selectedYear ?? now()->year);
        $currentMonth = __('wini.months')[$selectedMonth - 1] ?? now()->translatedFormat('F');
    @endphp

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
                    <select name="mes" class="rounded-md border-gray-300 text-sm focus:border-amber-800 focus:ring-amber-800">
                        @foreach(__('wini.months') as $index => $monthName)
                            <option value="{{ $index + 1 }}" @selected($selectedMonth === $index + 1)>{{ $monthName }}</option>
                        @endforeach
                    </select>
                    <input name="anio" type="number" value="{{ $selectedYear }}" min="2000" max="2100" class="w-24 rounded-md border-gray-300 text-sm focus:border-amber-800 focus:ring-amber-800">
                    <button class="rounded-md bg-stone-800 px-3 py-2 text-sm font-semibold text-white hover:bg-stone-900">Filtrar</button>
                </form>
                <a href="{{ route('ventas.create') }}" class="rounded-md bg-amber-900 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-950">
                    {{ __('wini.new_sale') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @php
                $marketLatest = $cocoaMarket['latest'] ?? null;
                $marketHistory = $cocoaMarket['history'] ?? collect();
                $marketDailyVariation = $cocoaMarket['dailyVariation'] ?? null;
                $marketWeeklyVariation = $cocoaMarket['weeklyVariation'] ?? null;
            @endphp

            <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-5 text-center shadow-sm sm:text-left">
                    <p class="text-sm font-medium text-emerald-700">{{ __('wini.monthly_income', ['month' => $currentMonth]) }}</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-950">${{ number_format($totalIngresos, 2) }}</p>
                </div>
                <div class="rounded-lg border border-red-200 bg-red-50 p-5 text-center shadow-sm sm:text-left">
                    <p class="text-sm font-medium text-red-700">{{ __('wini.monthly_expenses', ['month' => $currentMonth]) }}</p>
                    <p class="mt-2 text-3xl font-bold text-red-950">${{ number_format($totalGastos, 2) }}</p>
                </div>
                <div class="rounded-lg border {{ $gananciaNeta >= 0 ? 'border-amber-200 bg-amber-50' : 'border-red-200 bg-red-50' }} p-5 text-center shadow-sm sm:text-left">
                    <p class="text-sm font-medium {{ $gananciaNeta >= 0 ? 'text-amber-700' : 'text-red-700' }}">{{ __('wini.monthly_profit', ['month' => $currentMonth]) }}</p>
                    <p class="mt-2 text-3xl font-bold {{ $gananciaNeta >= 0 ? 'text-amber-950' : 'text-red-950' }}">
                        ${{ number_format($gananciaNeta, 2) }}
                    </p>
                </div>
                <div class="rounded-lg border border-sky-200 bg-sky-50 p-5 text-center shadow-sm sm:text-left">
                    <p class="text-sm font-medium text-sky-700">{{ __('wini.monthly_pounds_sold', ['month' => $currentMonth]) }}</p>
                    <p class="mt-2 text-3xl font-bold text-sky-950">{{ number_format($totalLibrasVendidas, 2) }}</p>
                </div>
            </section>

            <section class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-violet-200 bg-violet-50 p-5 text-center shadow-sm sm:text-left">
                    <p class="text-sm font-medium text-violet-700">Cliente principal</p>
                    <p class="mt-2 text-xl font-bold text-violet-950">{{ $clientePrincipal?->cliente?->nombre_comercial ?? 'Sin datos' }}</p>
                </div>
                <div class="rounded-lg border border-orange-200 bg-orange-50 p-5 text-center shadow-sm sm:text-left">
                    <p class="text-sm font-medium text-orange-700">Mayor gasto de {{ $currentMonth }}</p>
                    <p class="mt-2 text-xl font-bold text-orange-950">${{ number_format($mayorGasto, 2) }}</p>
                </div>
                <div class="rounded-lg border border-cyan-200 bg-cyan-50 p-5 text-center shadow-sm sm:text-left">
                    <p class="text-sm font-medium text-cyan-700">Precio promedio por libra</p>
                    <p class="mt-2 text-xl font-bold text-cyan-950">${{ number_format($precioPromedioLibra, 2) }}</p>
                </div>
                <div class="rounded-lg border border-stone-300 bg-stone-100 p-5 text-center shadow-sm sm:text-left">
                    <p class="text-sm font-medium text-stone-600">Inversiones de {{ $currentMonth }}</p>
                    <p class="mt-2 text-xl font-bold text-stone-950">${{ number_format($totalInversiones, 2) }}</p>
                </div>
            </section>

            <section class="mt-6 grid gap-6 lg:grid-cols-2">
                <div class="rounded-lg border border-gray-200 bg-white p-5 text-center shadow-sm sm:text-left">
                    <div class="flex flex-wrap items-start justify-center gap-4 sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Ventas, gastos e inversiones</h3>
                            <p class="mt-1 text-sm text-gray-500">Totales acumulados de todos los registros.</p>
                        </div>
                    </div>
                    <div class="mt-4 grid gap-3 sm:grid-cols-4">
                        <div class="rounded-md bg-amber-50 p-3">
                            <p class="text-xs font-medium uppercase text-amber-800">Total ingresos</p>
                            <p class="mt-1 text-lg font-bold text-amber-950">${{ number_format($totalIngresosGeneral, 2) }}</p>
                        </div>
                        <div class="rounded-md bg-amber-50 p-3">
                            <p class="text-xs font-medium uppercase text-amber-700">Total gastos</p>
                            <p class="mt-1 text-lg font-bold text-amber-900">${{ number_format($totalGastosGeneral, 2) }}</p>
                        </div>
                        <div class="rounded-md bg-stone-100 p-3">
                            <p class="text-xs font-medium uppercase text-stone-700">Total inversiones</p>
                            <p class="mt-1 text-lg font-bold text-stone-900">${{ number_format($totalInversionesGeneral, 2) }}</p>
                        </div>
                        <div class="rounded-md {{ $flujoGeneralDespuesInversion >= 0 ? 'bg-orange-50' : 'bg-red-50' }} p-3">
                            <p class="text-xs font-medium uppercase {{ $flujoGeneralDespuesInversion >= 0 ? 'text-orange-800' : 'text-red-700' }}">Ganancia total</p>
                            <p class="mt-1 text-lg font-bold {{ $flujoGeneralDespuesInversion >= 0 ? 'text-orange-950' : 'text-red-900' }}">${{ number_format($flujoGeneralDespuesInversion, 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-4 h-80">
                        <canvas id="ventasGastosChart"></canvas>
                    </div>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 text-center shadow-sm sm:text-left">
                    <div class="flex flex-wrap items-start justify-center gap-4 sm:justify-between">
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

            <section class="mt-6 grid gap-6 lg:grid-cols-[1fr_1.4fr]">
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-5 text-center shadow-sm sm:text-left">
                    <div class="flex flex-wrap items-start justify-center gap-4 sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-amber-800">Mercado del Cacao</p>
                            <h3 class="mt-1 text-lg font-bold text-amber-950">Precio internacional</h3>
                        </div>
                        <a href="{{ route('mercado-cacao.index') }}" class="rounded-md bg-amber-900 px-3 py-2 text-xs font-semibold text-white hover:bg-stone-950">Ver mercado</a>
                    </div>

                    <div class="mt-5">
                        <p class="text-sm text-amber-800">Precio actual</p>
                        <p id="cocoa-market-price" class="mt-1 text-4xl font-bold text-amber-950">
                            {{ $marketLatest ? '$'.number_format((float) $marketLatest->price, 2) : 'Sin datos' }}
                        </p>
                        <p id="cocoa-market-unit" class="mt-1 text-xs text-amber-800">{{ $marketLatest?->currency ?? config('cocoa_market.currency') }} / {{ $marketLatest?->unit ?? config('cocoa_market.unit') }}</p>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3">
                        <div class="rounded-md bg-white/70 p-3">
                            <p class="text-xs font-medium uppercase text-stone-500">Diaria</p>
                            <p id="cocoa-market-daily" class="mt-1 font-bold {{ ($marketDailyVariation ?? 0) >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                                {{ $marketDailyVariation !== null ? number_format($marketDailyVariation, 2).'%' : 'Sin datos' }}
                            </p>
                        </div>
                        <div class="rounded-md bg-white/70 p-3">
                            <p class="text-xs font-medium uppercase text-stone-500">Semanal</p>
                            <p id="cocoa-market-weekly" class="mt-1 font-bold {{ ($marketWeeklyVariation ?? 0) >= 0 ? 'text-sky-700' : 'text-red-700' }}">
                                {{ $marketWeeklyVariation !== null ? number_format($marketWeeklyVariation, 2).'%' : 'Sin datos' }}
                            </p>
                        </div>
                        <div class="rounded-md bg-white/70 p-3">
                            <p class="text-xs font-medium uppercase text-stone-500">Actualizado</p>
                            <p id="cocoa-market-updated" class="mt-1 font-bold text-stone-900">{{ $marketLatest?->quoted_at?->format('Y-m-d H:i') ?? 'Sin datos' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-5 text-center shadow-sm sm:text-left">
                    <div class="flex flex-wrap items-start justify-center gap-4 sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Historico del cacao</h3>
                            <p class="mt-1 text-sm text-gray-500">Ultimos 30 dias registrados.</p>
                        </div>
                    </div>
                    <div class="mt-4 h-72">
                        <canvas id="cocoaMarketChart"></canvas>
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
        const cocoaHistory = @json($marketHistory);

        new Chart(document.getElementById('ventasGastosChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Ingresos', data: ventasVsGastos.map(row => row.ingresos), backgroundColor: '#78350f' },
                    { label: 'Gastos', data: ventasVsGastos.map(row => row.gastos), backgroundColor: '#b45309' },
                    { label: 'Inversiones', data: ventasVsGastos.map(row => row.inversiones), backgroundColor: '#57534e' }
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
                        borderColor: '#78350f',
                        backgroundColor: 'rgba(120, 53, 15, .14)',
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

        const cocoaMarketChart = new Chart(document.getElementById('cocoaMarketChart'), {
            type: 'line',
            data: {
                labels: cocoaHistory.map(row => row.date),
                datasets: [
                    {
                        label: 'Precio cacao',
                        data: cocoaHistory.map(row => row.price),
                        borderColor: '#78350f',
                        backgroundColor: 'rgba(120, 53, 15, .12)',
                        fill: true,
                        tension: .25
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: false } }
            }
        });

        const moneyFormatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        const percentFormatter = new Intl.NumberFormat('es-EC', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        function paintVariation(element, value, positiveClass = 'text-emerald-700') {
            element.classList.remove('text-emerald-700', 'text-sky-700', 'text-red-700');
            element.classList.add(value >= 0 ? positiveClass : 'text-red-700');
            element.textContent = `${percentFormatter.format(value)}%`;
        }

        async function refreshCocoaMarket() {
            try {
                const response = await fetch(@json(route('mercado-cacao.live')), {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    return;
                }

                const data = await response.json();

                if (!data.latest) {
                    return;
                }

                document.getElementById('cocoa-market-price').textContent = moneyFormatter.format(data.latest.price);
                document.getElementById('cocoa-market-unit').textContent = `${data.latest.currency} / ${data.latest.unit}`;
                document.getElementById('cocoa-market-updated').textContent = data.latest.quoted_at ?? 'Sin datos';

                if (data.dailyVariation !== null) {
                    paintVariation(document.getElementById('cocoa-market-daily'), Number(data.dailyVariation), 'text-emerald-700');
                }

                if (data.weeklyVariation !== null) {
                    paintVariation(document.getElementById('cocoa-market-weekly'), Number(data.weeklyVariation), 'text-sky-700');
                }

                cocoaMarketChart.data.labels = data.history.map(row => row.date);
                cocoaMarketChart.data.datasets[0].data = data.history.map(row => row.price);
                cocoaMarketChart.update();
            } catch (error) {
                console.warn('No se pudo actualizar el mercado del cacao en tiempo real.', error);
            }
        }

        setTimeout(refreshCocoaMarket, 3000);
        setInterval(refreshCocoaMarket, 60000);
    </script>
</x-app-layout>
