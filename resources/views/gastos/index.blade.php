<x-layouts.app title="Gastos | Wini">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Gastos</h1>
            <p class="mt-1 text-stone-600">Costos operativos generados por la venta de cacao.</p>
        </div>
        <a href="{{ route('gastos.create') }}" class="rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800">Nuevo gasto</a>
    </div>

    <form class="mt-6 flex flex-wrap gap-3 rounded-lg border border-stone-200 bg-white p-4 shadow-sm">
        <input type="month" name="mes" value="{{ request('mes') }}" class="rounded-md border border-stone-300 px-3 py-2">
        <input type="date" name="desde" value="{{ request('desde') }}" class="rounded-md border border-stone-300 px-3 py-2">
        <input type="date" name="hasta" value="{{ request('hasta') }}" class="rounded-md border border-stone-300 px-3 py-2">
        <button class="rounded-md bg-stone-900 px-4 py-2 font-semibold text-white">Filtrar</button>
        <a href="{{ route('gastos.index') }}" class="rounded-md border border-stone-300 px-4 py-2 font-semibold">Limpiar</a>
    </form>

    <div class="mt-6 overflow-x-auto rounded-lg border border-stone-200 bg-white shadow-sm">
        <table class="w-full min-w-[880px] text-left text-sm">
            <thead class="bg-stone-100 text-stone-600">
                <tr><th class="p-3">Fecha</th><th class="p-3">Tipo</th><th class="p-3">Descripcion</th><th class="p-3">Monto</th><th class="p-3">Usuario</th><th class="p-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($gastos as $gasto)
                    <tr>
                        <td class="p-3">{{ $gasto->fecha->format('Y-m-d') }}</td>
                        <td class="p-3">{{ str_replace('_', ' ', ucfirst($gasto->tipo)) }}</td>
                        <td class="p-3">{{ $gasto->descripcion ?: 'Sin descripcion' }}</td>
                        <td class="p-3 font-semibold">${{ number_format($gasto->monto, 2) }}</td>
                        <td class="p-3">{{ $gasto->user?->name ?? 'Sin usuario' }}</td>
                        <td class="p-3">
                            <div class="flex justify-end gap-2">
                                @can('update', $gasto)
                                    <a href="{{ route('gastos.edit', $gasto) }}" class="rounded-md border border-stone-300 px-3 py-1.5 font-semibold hover:bg-stone-100">Editar</a>
                                @endcan
                                @can('delete', $gasto)
                                    <form method="POST" action="{{ route('gastos.destroy', $gasto) }}" onsubmit="return confirm('Eliminar este gasto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-md bg-red-700 px-3 py-1.5 font-semibold text-white hover:bg-red-800">Eliminar</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-6 text-center text-stone-500">No hay gastos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $gastos->links() }}</div>
</x-layouts.app>
