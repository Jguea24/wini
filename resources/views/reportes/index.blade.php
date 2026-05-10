<x-layouts.app title="Reportes | Wini">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            @if (file_exists(public_path('images/wini-logo.png')))
                <img src="{{ asset('images/wini-logo.png') }}" alt="Wini" class="h-20 w-auto object-contain">
            @else
                <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-emerald-900 text-lg font-bold text-white">Wini</div>
            @endif
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Reportes</h1>
                <p class="mt-1 text-stone-600">Reporte mensual o por rango de fechas.</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reportes.excel', request()->query()) }}" class="rounded-md border border-stone-300 bg-white px-4 py-2 font-semibold hover:bg-stone-100">Excel</a>
            <a href="{{ route('reportes.pdf', request()->query()) }}" class="rounded-md bg-stone-900 px-4 py-2 font-semibold text-white hover:bg-stone-700">PDF</a>
        </div>
    </div>

    <form class="mt-6 grid gap-3 rounded-lg border border-stone-200 bg-white p-4 shadow-sm md:grid-cols-[160px_120px_120px_1fr_1fr_auto]">
        <select name="tipo_filtro" class="rounded-md border border-stone-300 px-3 py-2">
            <option value="mes" @selected(($tipoFiltro ?? 'mes') === 'mes')>Mensual</option>
            <option value="rango" @selected(($tipoFiltro ?? 'mes') === 'rango')>Rango</option>
        </select>
        <input type="number" name="mes" min="1" max="12" value="{{ request('mes', $mes ?? now()->month) }}" class="rounded-md border border-stone-300 px-3 py-2">
        <input type="number" name="anio" min="2000" max="2100" value="{{ request('anio', $anio ?? now()->year) }}" class="rounded-md border border-stone-300 px-3 py-2">
        <input type="date" name="desde" value="{{ request('desde', $desde) }}" class="rounded-md border border-stone-300 px-3 py-2">
        <input type="date" name="hasta" value="{{ request('hasta', $hasta) }}" class="rounded-md border border-stone-300 px-3 py-2">
        <button class="rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">Generar</button>
    </form>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-stone-500">Periodo</p>
            <p class="mt-2 text-lg font-bold">{{ $desde }} / {{ $hasta }}</p>
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-stone-500">Libras vendidas</p>
            <p class="mt-2 text-3xl font-bold">{{ number_format($totalLibrasVendidas, 2) }}</p>
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-stone-500">Ingresos</p>
            <p class="mt-2 text-3xl font-bold">${{ number_format($totalIngresos, 2) }}</p>
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-stone-500">Gastos</p>
            <p class="mt-2 text-3xl font-bold">${{ number_format($totalGastos, 2) }}</p>
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm sm:col-span-2 lg:col-span-4">
            <p class="text-sm text-stone-500">Ganancia neta</p>
            <p class="mt-2 text-4xl font-bold {{ $gananciaNeta >= 0 ? 'text-emerald-800' : 'text-red-700' }}">${{ number_format($gananciaNeta, 2) }}</p>
        </div>
    </section>

    <section class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-semibold">Ventas por cliente</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-stone-500"><tr><th class="py-2">Cliente</th><th class="py-2 text-right">Total</th></tr></thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($ventasPorCliente as $ventaCliente)
                            <tr><td class="py-2">{{ $ventaCliente->cliente?->nombre_comercial ?? 'Sin cliente' }}</td><td class="py-2 text-right font-semibold">${{ number_format($ventaCliente->total_cliente, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="py-4 text-center text-stone-500">Sin ventas en el periodo.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-semibold">Gastos por tipo</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-stone-500"><tr><th class="py-2">Tipo</th><th class="py-2 text-right">Total</th></tr></thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($gastosPorTipo as $gastoTipo)
                            <tr><td class="py-2">{{ str_replace('_', ' ', ucfirst($gastoTipo->tipo)) }}</td><td class="py-2 text-right font-semibold">${{ number_format($gastoTipo->total_tipo, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="py-4 text-center text-stone-500">Sin gastos en el periodo.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-layouts.app>
