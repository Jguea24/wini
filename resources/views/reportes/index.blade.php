<x-layouts.app title="Reportes | Wini">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-4">
            @if (file_exists(public_path('images/wini-logo.png')))
                <img src="{{ asset('images/wini-logo.png') }}" alt="Wini" class="h-16 w-auto object-contain">
            @else
                <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-amber-900 text-sm font-bold text-white">Wini</div>
            @endif
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-amber-900">Analisis financiero</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-stone-950">Reportes</h1>
                <p class="mt-1 text-sm text-stone-500">Resumen mensual o por rango de fechas.</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('reportes.excel', request()->query()) }}" class="btn-ghost">Excel</a>
            <a href="{{ route('reportes.pdf', request()->query()) }}" class="btn-cacao">PDF</a>
        </div>
    </div>

    <form class="app-card grid gap-4 p-5 lg:grid-cols-[1.1fr_.8fr_.8fr_1fr_1fr_auto] lg:items-end">
        <div class="floating-control">
            <label for="tipo_filtro" class="floating-label">Filtro</label>
            <select id="tipo_filtro" name="tipo_filtro">
                <option value="mes" @selected(($tipoFiltro ?? 'mes') === 'mes')>Mensual</option>
                <option value="rango" @selected(($tipoFiltro ?? 'mes') === 'rango')>Rango</option>
            </select>
        </div>
        <div class="floating-control">
            <label for="mes" class="floating-label">Mes</label>
            <input id="mes" type="number" name="mes" min="1" max="12" value="{{ request('mes', $mes ?? now()->month) }}">
        </div>
        <div class="floating-control">
            <label for="anio" class="floating-label">Anio</label>
            <input id="anio" type="number" name="anio" min="2000" max="2100" value="{{ request('anio', $anio ?? now()->year) }}">
        </div>
        <div class="floating-control">
            <label for="desde" class="floating-label">Desde</label>
            <input id="desde" type="date" name="desde" value="{{ request('desde', $desde) }}">
        </div>
        <div class="floating-control">
            <label for="hasta" class="floating-label">Hasta</label>
            <input id="hasta" type="date" name="hasta" value="{{ request('hasta', $hasta) }}">
        </div>
        <button class="btn-cacao h-11">Generar</button>
    </form>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="app-card p-5">
            <p class="text-sm font-medium text-stone-500">Periodo</p>
            <p class="mt-2 text-lg font-bold text-stone-950">{{ $desde }} / {{ $hasta }}</p>
        </div>
        <div class="app-card p-5">
            <p class="text-sm font-medium text-stone-500">Libras vendidas</p>
            <p class="mt-2 text-3xl font-bold text-stone-950">{{ number_format($totalLibrasVendidas, 2) }}</p>
        </div>
        <div class="app-card p-5">
            <p class="text-sm font-medium text-stone-500">Ingresos</p>
            <p class="mt-2 text-3xl font-bold text-amber-900">${{ number_format($totalIngresos, 2) }}</p>
        </div>
        <div class="app-card p-5">
            <p class="text-sm font-medium text-stone-500">Gastos</p>
            <p class="mt-2 text-3xl font-bold text-red-700">${{ number_format($totalGastos, 2) }}</p>
        </div>
        <div class="app-card p-5">
            <p class="text-sm font-medium text-stone-500">Inversiones</p>
            <p class="mt-2 text-3xl font-bold text-stone-950">${{ number_format($totalInversiones, 2) }}</p>
        </div>
        <div class="app-card p-5 sm:col-span-2 lg:col-span-3">
            <p class="text-sm font-medium text-stone-500">Ganancia neta</p>
            <p class="mt-2 text-4xl font-bold {{ $gananciaNeta >= 0 ? 'text-amber-900' : 'text-red-700' }}">${{ number_format($gananciaNeta, 2) }}</p>
        </div>
        <div class="app-card p-5 sm:col-span-2">
            <p class="text-sm font-medium text-stone-500">Flujo despues de inversion</p>
            <p class="mt-2 text-4xl font-bold {{ $flujoDespuesInversion >= 0 ? 'text-amber-900' : 'text-red-700' }}">${{ number_format($flujoDespuesInversion, 2) }}</p>
        </div>
    </section>

    <section class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="app-card overflow-hidden">
            <div class="border-b border-stone-100 px-5 py-4">
                <h2 class="font-semibold text-stone-950">Ventas por cliente</h2>
                <p class="text-sm text-stone-500">Clientes con mayor compra en el periodo.</p>
            </div>
            <div class="overflow-x-auto p-5">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-stone-500">
                        <tr><th class="py-2">Cliente</th><th class="py-2 text-right">Total</th></tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($ventasPorCliente as $ventaCliente)
                            <tr><td class="py-3 font-medium text-stone-950">{{ $ventaCliente->cliente?->nombre_comercial ?? 'Sin cliente' }}</td><td class="py-3 text-right font-bold text-amber-900">${{ number_format($ventaCliente->total_cliente, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="py-8 text-center text-stone-500">Sin ventas en el periodo.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="app-card overflow-hidden">
            <div class="border-b border-stone-100 px-5 py-4">
                <h2 class="font-semibold text-stone-950">Gastos por tipo</h2>
                <p class="text-sm text-stone-500">Distribucion de costos operativos.</p>
            </div>
            <div class="overflow-x-auto p-5">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-stone-500">
                        <tr><th class="py-2">Tipo</th><th class="py-2 text-right">Total</th></tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($gastosPorTipo as $gastoTipo)
                            <tr><td class="py-3 font-medium text-stone-950">{{ str_replace('_', ' ', ucfirst($gastoTipo->tipo)) }}</td><td class="py-3 text-right font-bold text-red-700">${{ number_format($gastoTipo->total_tipo, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="py-8 text-center text-stone-500">Sin gastos en el periodo.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="app-card overflow-hidden">
            <div class="border-b border-stone-100 px-5 py-4">
                <h2 class="font-semibold text-stone-950">Inversiones por tipo</h2>
                <p class="text-sm text-stone-500">Destino del capital invertido.</p>
            </div>
            <div class="overflow-x-auto p-5">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-stone-500">
                        <tr><th class="py-2">Tipo</th><th class="py-2 text-right">Total</th></tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($inversionesPorTipo as $inversionTipo)
                            <tr><td class="py-3 font-medium text-stone-950">{{ str_replace('_', ' ', ucfirst($inversionTipo->tipo)) }}</td><td class="py-3 text-right font-bold text-amber-900">${{ number_format($inversionTipo->total_tipo, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="py-8 text-center text-stone-500">Sin inversiones en el periodo.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-layouts.app>
