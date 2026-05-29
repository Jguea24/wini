<x-layouts.app title="Facturas | Wini">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-amber-900">Comprobantes</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-stone-950">Facturas</h1>
            <p class="mt-1 text-sm text-stone-500">Comprobantes generados desde las ventas de cacao.</p>
        </div>
        <a href="{{ route('ventas.index') }}" class="btn-cacao">Facturar venta</a>
    </div>

    <form class="app-card grid gap-4 p-5 md:grid-cols-[1.2fr_1fr_1fr_auto_auto] md:items-end">
        <div class="floating-control">
            <label for="estado" class="floating-label">Estado</label>
            <select id="estado" name="estado">
                <option value="">Todos los estados</option>
                @foreach (\App\Models\Factura::ESTADOS as $estado)
                    <option value="{{ $estado }}" @selected(request('estado') === $estado)>{{ ucfirst($estado) }}</option>
                @endforeach
            </select>
        </div>
        <div class="floating-control">
            <label for="desde" class="floating-label">Desde</label>
            <input id="desde" type="date" name="desde" value="{{ request('desde') }}">
        </div>
        <div class="floating-control">
            <label for="hasta" class="floating-label">Hasta</label>
            <input id="hasta" type="date" name="hasta" value="{{ request('hasta') }}">
        </div>
        <button class="btn-cacao h-11">Filtrar</button>
        <a href="{{ route('facturas.index') }}" class="btn-ghost h-11 text-center">Limpiar</a>
    </form>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="app-card p-5">
            <p class="text-sm font-medium text-stone-500">Total facturado</p>
            <p class="mt-2 text-3xl font-bold text-amber-900">${{ number_format($totalFacturado, 2) }}</p>
        </div>
        <div class="app-card p-5">
            <p class="text-sm font-medium text-stone-500">Facturas</p>
            <p class="mt-2 text-3xl font-bold text-stone-950">{{ $facturas->total() }}</p>
        </div>
    </section>

    <div class="app-card mt-6 overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-stone-950">Registro de facturas</h2>
                <p class="text-sm text-stone-500">Consulta el estado y total de cada comprobante.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px] text-left text-sm">
                <thead class="bg-stone-50 text-xs font-semibold uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="px-5 py-3">Numero</th>
                        <th class="px-5 py-3">Fecha</th>
                        <th class="px-5 py-3">Cliente</th>
                        <th class="px-5 py-3">Venta</th>
                        <th class="px-5 py-3 text-right">Total</th>
                        <th class="px-5 py-3">Estado</th>
                        <th class="px-5 py-3">Usuario</th>
                        <th class="px-5 py-3">Ultimo cambio</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($facturas as $factura)
                        <tr class="hover:bg-amber-50/40">
                            <td class="px-5 py-4 font-semibold text-stone-950">{{ $factura->numero }}</td>
                            <td class="px-5 py-4 text-stone-600">{{ $factura->fecha_emision->format('Y-m-d') }}</td>
                            <td class="px-5 py-4 text-stone-700">{{ $factura->venta?->cliente?->nombre_comercial ?? 'Sin cliente' }}</td>
                            <td class="px-5 py-4 text-stone-600">#{{ $factura->venta_id }}</td>
                            <td class="px-5 py-4 text-right font-bold text-amber-900">${{ number_format($factura->total, 2) }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-900">{{ ucfirst($factura->estado) }}</span>
                            </td>
                            <td class="px-5 py-4 text-stone-600">{{ $factura->user?->name ?? 'Sin usuario' }}</td>
                            <td class="px-5 py-4 text-stone-600">{{ $factura->actualizador?->name ?? 'Sin cambios' }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('facturas.show', $factura) }}" class="rounded-md border border-stone-300 px-3 py-1.5 font-semibold text-stone-700 hover:bg-stone-50">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center">
                                <p class="font-semibold text-stone-700">No hay facturas registradas.</p>
                                <p class="mt-1 text-sm text-stone-500">Primero registra una venta y luego genera su factura.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $facturas->links() }}</div>
</x-layouts.app>
