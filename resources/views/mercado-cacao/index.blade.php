<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Mercado del Cacao</h2>
                <p class="mt-1 text-sm text-gray-500">Precio internacional del cacao y tendencia historica de los ultimos 30 dias.</p>
            </div>
            <form method="POST" action="{{ route('mercado-cacao.refresh') }}">
                @csrf
                <button class="rounded-md bg-amber-900 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-950">
                    Actualizar precio
                </button>
            </form>
        </div>
    </x-slot>

    @php
        $latest = $cocoaMarket['latest'];
        $history = $cocoaMarket['history'];
        $dailyVariation = $cocoaMarket['dailyVariation'];
        $weeklyVariation = $cocoaMarket['weeklyVariation'];
    @endphp

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-5 shadow-sm">
                    <p class="text-sm font-medium text-amber-700">Precio actual del cacao</p>
                    <p class="mt-2 text-3xl font-bold text-amber-950">
                        {{ $latest ? '$'.number_format((float) $latest->price, 2) : 'Sin datos' }}
                    </p>
                    <p class="mt-1 text-xs text-amber-800">{{ $latest?->currency ?? config('cocoa_market.currency') }} / {{ $latest?->unit ?? config('cocoa_market.unit') }}</p>
                </div>
                <div class="rounded-lg border {{ ($dailyVariation ?? 0) >= 0 ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50' }} p-5 shadow-sm">
                    <p class="text-sm font-medium {{ ($dailyVariation ?? 0) >= 0 ? 'text-emerald-700' : 'text-red-700' }}">Variacion diaria</p>
                    <p class="mt-2 text-3xl font-bold {{ ($dailyVariation ?? 0) >= 0 ? 'text-emerald-950' : 'text-red-950' }}">
                        {{ $dailyVariation !== null ? number_format($dailyVariation, 2).'%' : 'Sin datos' }}
                    </p>
                </div>
                <div class="rounded-lg border {{ ($weeklyVariation ?? 0) >= 0 ? 'border-sky-200 bg-sky-50' : 'border-red-200 bg-red-50' }} p-5 shadow-sm">
                    <p class="text-sm font-medium {{ ($weeklyVariation ?? 0) >= 0 ? 'text-sky-700' : 'text-red-700' }}">Variacion semanal</p>
                    <p class="mt-2 text-3xl font-bold {{ ($weeklyVariation ?? 0) >= 0 ? 'text-sky-950' : 'text-red-950' }}">
                        {{ $weeklyVariation !== null ? number_format($weeklyVariation, 2).'%' : 'Sin datos' }}
                    </p>
                </div>
                <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-stone-500">Ultima actualizacion</p>
                    <p class="mt-2 text-xl font-bold text-stone-950">{{ $latest?->quoted_at?->format('Y-m-d H:i') ?? 'Sin datos' }}</p>
                    <p class="mt-1 text-xs text-stone-500">Fuente: {{ $latest?->provider ?? config('cocoa_market.provider') }}</p>
                </div>
            </section>

            <section class="mt-6 rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Historico de precio</h3>
                        <p class="mt-1 text-sm text-gray-500">Ultimos 30 dias registrados en la base de datos.</p>
                    </div>
                </div>
                <div class="mt-4 h-96">
                    <canvas id="cocoaMarketChart"></canvas>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const cocoaHistory = @json($history);

        new Chart(document.getElementById('cocoaMarketChart'), {
            type: 'line',
            data: {
                labels: cocoaHistory.map(row => row.date),
                datasets: [{
                    label: 'Precio cacao',
                    data: cocoaHistory.map(row => row.price),
                    borderColor: '#78350f',
                    backgroundColor: 'rgba(120, 53, 15, .12)',
                    fill: true,
                    tension: .25
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: false } }
            }
        });
    </script>
</x-app-layout>
