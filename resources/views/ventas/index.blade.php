<x-layouts.app title="Ventas | Wini">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-amber-900">Modulo comercial</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-stone-950">Ventas</h1>
            <p class="mt-1 text-sm text-stone-500">Cacao vendido a empresas compradoras e intermediarios.</p>
        </div>
        <a href="{{ route('ventas.create') }}" class="btn-cacao">Nueva venta</a>
    </div>

    <form class="app-card grid gap-4 p-5 md:grid-cols-[1fr_1fr_auto_auto] md:items-end">
        <div class="floating-control">
            <label for="desde" class="floating-label">Desde</label>
            <input id="desde" type="date" name="desde" value="{{ request('desde') }}">
        </div>
        <div class="floating-control">
            <label for="hasta" class="floating-label">Hasta</label>
            <input id="hasta" type="date" name="hasta" value="{{ request('hasta') }}">
        </div>
        <button class="btn-cacao h-11">Filtrar</button>
        <a href="{{ route('ventas.index') }}" class="btn-ghost h-11 text-center">Limpiar</a>
    </form>

    <div class="app-card mt-6 overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-stone-950">Registro de ventas</h2>
                <p class="text-sm text-stone-500">{{ $ventas->total() }} registros encontrados</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1080px] text-left text-sm">
                <thead class="bg-stone-50 text-xs font-semibold uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="px-5 py-3">Fecha</th>
                        <th class="px-5 py-3">Cliente</th>
                        <th class="px-5 py-3">Empresa</th>
                        <th class="px-5 py-3 text-right">Libras</th>
                        <th class="px-5 py-3 text-right">Precio/lb</th>
                        <th class="px-5 py-3 text-right">Total</th>
                        <th class="px-5 py-3">Pago</th>
                        <th class="px-5 py-3">Factura</th>
                        <th class="px-5 py-3">Usuario</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($ventas as $venta)
                        <tr class="hover:bg-amber-50/40">
                            <td class="px-5 py-4 text-stone-600">{{ $venta->fecha->format('Y-m-d') }}</td>
                            <td class="px-5 py-4 font-semibold text-stone-950">{{ $venta->cliente->nombre }}</td>
                            <td class="px-5 py-4 text-stone-600">{{ $venta->cliente->empresa ?: 'Sin empresa' }}</td>
                            <td class="px-5 py-4 text-right text-stone-700">{{ number_format($venta->libras, 2) }}</td>
                            <td class="px-5 py-4 text-right text-stone-700">${{ number_format($venta->precio_por_libra, 2) }}</td>
                            <td class="px-5 py-4 text-right font-bold text-amber-900">${{ number_format($venta->total, 2) }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-stone-700">{{ ucfirst($venta->metodo_pago) }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @if ($venta->factura)
                                    <a href="{{ route('facturas.show', $venta->factura) }}" class="font-semibold text-amber-900 hover:text-stone-950">{{ $venta->factura->numero }}</a>
                                @else
                                    <form method="POST" action="{{ route('facturas.store') }}">
                                        @csrf
                                        <input type="hidden" name="venta_id" value="{{ $venta->id }}">
                                        <button class="rounded-md bg-stone-950 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-amber-900">Facturar</button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-stone-600">{{ $venta->user?->name ?? 'Sin usuario' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    @can('update', $venta)
                                        <a href="{{ route('ventas.edit', $venta) }}" class="rounded-md border border-stone-300 px-3 py-1.5 font-semibold text-stone-700 hover:bg-stone-50">Editar</a>
                                    @endcan
                                    @can('delete', $venta)
                                        <form method="POST" action="{{ route('ventas.destroy', $venta) }}" onsubmit="return confirm('Eliminar esta venta?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-md bg-red-700 px-3 py-1.5 font-semibold text-white hover:bg-red-800">Eliminar</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-12 text-center">
                                <p class="font-semibold text-stone-700">No hay ventas registradas.</p>
                                <p class="mt-1 text-sm text-stone-500">Cuando registres una venta aparecera en este listado.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $ventas->links() }}</div>
</x-layouts.app>
