<x-layouts.app title="Inversiones | Wini">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-amber-900">Capital del negocio</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-stone-950">Inversiones</h1>
            <p class="mt-1 text-sm text-stone-500">Capital destinado a infraestructura, equipos, siembra y mejoras.</p>
        </div>
        <a href="{{ route('inversiones.create') }}" class="btn-cacao">Nueva inversion</a>
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
        <a href="{{ route('inversiones.index') }}" class="btn-ghost h-11 text-center">Limpiar</a>
    </form>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="app-card p-5">
            <p class="text-sm font-medium text-stone-500">Total invertido</p>
            <p class="mt-2 text-3xl font-bold text-amber-900">${{ number_format($totalInversiones, 2) }}</p>
        </div>
        <div class="app-card p-5">
            <p class="text-sm font-medium text-stone-500">Registros</p>
            <p class="mt-2 text-3xl font-bold text-stone-950">{{ $inversiones->total() }}</p>
        </div>
    </section>

    <div class="app-card mt-6 overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-stone-950">Registro de inversiones</h2>
                <p class="text-sm text-stone-500">Movimientos de capital para el crecimiento del negocio.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[960px] text-left text-sm">
                <thead class="bg-stone-50 text-xs font-semibold uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="px-5 py-3">Fecha</th>
                        <th class="px-5 py-3">Tipo</th>
                        <th class="px-5 py-3">Concepto</th>
                        <th class="px-5 py-3">Descripcion</th>
                        <th class="px-5 py-3 text-right">Monto</th>
                        <th class="px-5 py-3">Usuario</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($inversiones as $inversion)
                        <tr class="hover:bg-amber-50/40">
                            <td class="px-5 py-4 text-stone-600">{{ $inversion->fecha->format('Y-m-d') }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-900">{{ str_replace('_', ' ', ucfirst($inversion->tipo)) }}</span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-stone-950">{{ $inversion->concepto }}</td>
                            <td class="px-5 py-4 text-stone-700">{{ $inversion->descripcion ?: 'Sin descripcion' }}</td>
                            <td class="px-5 py-4 text-right font-bold text-amber-900">${{ number_format($inversion->monto, 2) }}</td>
                            <td class="px-5 py-4 text-stone-600">{{ $inversion->user?->name ?? 'Sin usuario' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    @can('update', $inversion)
                                        <a href="{{ route('inversiones.edit', $inversion) }}" class="rounded-md border border-stone-300 px-3 py-1.5 font-semibold text-stone-700 hover:bg-stone-50">Editar</a>
                                    @endcan
                                    @can('delete', $inversion)
                                        <form method="POST" action="{{ route('inversiones.destroy', $inversion) }}" onsubmit="return confirm('Eliminar esta inversion?')">
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
                            <td colspan="7" class="px-5 py-12 text-center">
                                <p class="font-semibold text-stone-700">No hay inversiones registradas.</p>
                                <p class="mt-1 text-sm text-stone-500">Las inversiones apareceran aqui cuando las registres.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $inversiones->links() }}</div>
</x-layouts.app>
