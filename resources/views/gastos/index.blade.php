<x-layouts.app title="Gastos | Wini">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-amber-900">Control operativo</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-stone-950">Gastos</h1>
            <p class="mt-1 text-sm text-stone-500">Costos generados por la operacion y venta de cacao.</p>
        </div>
        <a href="{{ route('gastos.create') }}" class="btn-cacao">Nuevo gasto</a>
    </div>

    <form class="app-card grid gap-4 p-5 md:grid-cols-[1fr_1fr_1fr_auto_auto] md:items-end">
        <div class="floating-control">
            <label for="mes" class="floating-label">Mes</label>
            <input id="mes" type="month" name="mes" value="{{ request('mes') }}">
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
        <a href="{{ route('gastos.index') }}" class="btn-ghost h-11 text-center">Limpiar</a>
    </form>

    <div class="app-card mt-6 overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-stone-950">Registro de gastos</h2>
                <p class="text-sm text-stone-500">{{ $gastos->total() }} registros encontrados</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[880px] text-left text-sm">
                <thead class="bg-stone-50 text-xs font-semibold uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="px-5 py-3">Fecha</th>
                        <th class="px-5 py-3">Tipo</th>
                        <th class="px-5 py-3">Descripcion</th>
                        <th class="px-5 py-3 text-right">Monto</th>
                        <th class="px-5 py-3">Usuario</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($gastos as $gasto)
                        <tr class="hover:bg-amber-50/40">
                            <td class="px-5 py-4 text-stone-600">{{ $gasto->fecha->format('Y-m-d') }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-900">{{ str_replace('_', ' ', ucfirst($gasto->tipo)) }}</span>
                            </td>
                            <td class="px-5 py-4 text-stone-700">{{ $gasto->descripcion ?: 'Sin descripcion' }}</td>
                            <td class="px-5 py-4 text-right font-bold text-amber-900">${{ number_format($gasto->monto, 2) }}</td>
                            <td class="px-5 py-4 text-stone-600">{{ $gasto->user?->name ?? 'Sin usuario' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    @can('update', $gasto)
                                        <a href="{{ route('gastos.edit', $gasto) }}" class="rounded-md border border-stone-300 px-3 py-1.5 font-semibold text-stone-700 hover:bg-stone-50">Editar</a>
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
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="font-semibold text-stone-700">No hay gastos registrados.</p>
                                <p class="mt-1 text-sm text-stone-500">Los gastos operativos apareceran aqui.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $gastos->links() }}</div>
</x-layouts.app>
