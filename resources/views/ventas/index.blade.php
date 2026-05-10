<x-layouts.app title="Ventas | Wini">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Ventas</h1>
            <p class="mt-1 text-stone-600">Cacao vendido a empresas compradoras.</p>
        </div>
        <a href="{{ route('ventas.create') }}" class="rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800">Nueva venta</a>
    </div>

    <form class="mt-6 flex flex-wrap gap-3 rounded-lg border border-stone-200 bg-white p-4 shadow-sm">
        <input type="date" name="desde" value="{{ request('desde') }}" class="rounded-md border border-stone-300 px-3 py-2">
        <input type="date" name="hasta" value="{{ request('hasta') }}" class="rounded-md border border-stone-300 px-3 py-2">
        <button class="rounded-md bg-stone-900 px-4 py-2 font-semibold text-white">Filtrar</button>
        <a href="{{ route('ventas.index') }}" class="rounded-md border border-stone-300 px-4 py-2 font-semibold">Limpiar</a>
    </form>

    <div class="mt-6 overflow-x-auto rounded-lg border border-stone-200 bg-white shadow-sm">
        <table class="w-full min-w-[980px] text-left text-sm">
            <thead class="bg-stone-100 text-stone-600">
                <tr>
                    <th class="p-3">Fecha</th><th class="p-3">Cliente</th><th class="p-3">Empresa</th><th class="p-3">Libras</th><th class="p-3">Precio/lb</th><th class="p-3">Total</th><th class="p-3">Pago</th><th class="p-3">Usuario</th><th class="p-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($ventas as $venta)
                    <tr>
                        <td class="p-3">{{ $venta->fecha->format('Y-m-d') }}</td>
                        <td class="p-3">{{ $venta->cliente->nombre }}</td>
                        <td class="p-3">{{ $venta->cliente->empresa ?: 'Sin empresa' }}</td>
                        <td class="p-3">{{ number_format($venta->libras, 2) }}</td>
                        <td class="p-3">${{ number_format($venta->precio_por_libra, 2) }}</td>
                        <td class="p-3 font-semibold">${{ number_format($venta->total, 2) }}</td>
                        <td class="p-3">{{ ucfirst($venta->metodo_pago) }}</td>
                        <td class="p-3">{{ $venta->user?->name ?? 'Sin usuario' }}</td>
                        <td class="p-3">
                            <div class="flex justify-end gap-2">
                                @can('update', $venta)
                                    <a href="{{ route('ventas.edit', $venta) }}" class="rounded-md border border-stone-300 px-3 py-1.5 font-semibold hover:bg-stone-100">Editar</a>
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
                    <tr><td colspan="9" class="p-6 text-center text-stone-500">No hay ventas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $ventas->links() }}</div>
</x-layouts.app>
