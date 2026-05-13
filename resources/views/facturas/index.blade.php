<x-layouts.app title="Facturas | Wini">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Facturas</h1>
            <p class="mt-1 text-stone-600">Comprobantes generados desde las ventas de cacao.</p>
        </div>
        <a href="{{ route('ventas.index') }}" class="rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800">Facturar venta</a>
    </div>

    <form class="mt-6 flex flex-wrap gap-3 rounded-lg border border-stone-200 bg-white p-4 shadow-sm">
        <select name="estado" class="rounded-md border border-stone-300 px-3 py-2">
            <option value="">Todos los estados</option>
            @foreach (\App\Models\Factura::ESTADOS as $estado)
                <option value="{{ $estado }}" @selected(request('estado') === $estado)>{{ ucfirst($estado) }}</option>
            @endforeach
        </select>
        <input type="date" name="desde" value="{{ request('desde') }}" class="rounded-md border border-stone-300 px-3 py-2">
        <input type="date" name="hasta" value="{{ request('hasta') }}" class="rounded-md border border-stone-300 px-3 py-2">
        <button class="rounded-md bg-stone-900 px-4 py-2 font-semibold text-white">Filtrar</button>
        <a href="{{ route('facturas.index') }}" class="rounded-md border border-stone-300 px-4 py-2 font-semibold">Limpiar</a>
    </form>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-stone-500">Total facturado</p>
            <p class="mt-2 text-3xl font-bold text-emerald-800">${{ number_format($totalFacturado, 2) }}</p>
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-stone-500">Facturas</p>
            <p class="mt-2 text-3xl font-bold text-stone-900">{{ $facturas->total() }}</p>
        </div>
    </section>

    <div class="mt-6 overflow-x-auto rounded-lg border border-stone-200 bg-white shadow-sm">
        <table class="w-full min-w-[900px] text-left text-sm">
            <thead class="bg-stone-100 text-stone-600">
                <tr>
                    <th class="p-3">Numero</th>
                    <th class="p-3">Fecha</th>
                    <th class="p-3">Cliente</th>
                    <th class="p-3">Venta</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Estado</th>
                    <th class="p-3">Usuario</th>
                    <th class="p-3">Último cambio</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($facturas as $factura)
                    <tr>
                        <td class="p-3 font-semibold">{{ $factura->numero }}</td>
                        <td class="p-3">{{ $factura->fecha_emision->format('Y-m-d') }}</td>
                        <td class="p-3">{{ $factura->venta?->cliente?->nombre_comercial ?? 'Sin cliente' }}</td>
                        <td class="p-3">#{{ $factura->venta_id }}</td>
                        <td class="p-3 font-semibold">${{ number_format($factura->total, 2) }}</td>
                        <td class="p-3">{{ ucfirst($factura->estado) }}</td>
                        <td class="p-3">{{ $factura->user?->name ?? 'Sin usuario' }}</td>
                        <td class="p-3">{{ $factura->actualizador?->name ?? 'Sin cambios' }}</td>
                        <td class="p-3 text-right">
                            <a href="{{ route('facturas.show', $factura) }}" class="rounded-md border border-stone-300 px-3 py-1.5 font-semibold hover:bg-stone-100">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="p-6 text-center text-stone-500">No hay facturas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $facturas->links() }}</div>
</x-layouts.app>
