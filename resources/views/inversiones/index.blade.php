<x-layouts.app title="Inversiones | Wini">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Inversiones realizadas</h1>
            <p class="mt-1 text-stone-600">Capital destinado a infraestructura, equipos, siembra y mejoras del negocio.</p>
        </div>
        <a href="{{ route('inversiones.create') }}" class="rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800">Nueva inversion</a>
    </div>

    @if (session('status'))
        <div class="mt-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <form class="mt-6 flex flex-wrap gap-3 rounded-lg border border-stone-200 bg-white p-4 shadow-sm">
        <input type="month" name="mes" value="{{ request('mes') }}" class="rounded-md border border-stone-300 px-3 py-2">
        <input type="date" name="desde" value="{{ request('desde') }}" class="rounded-md border border-stone-300 px-3 py-2">
        <input type="date" name="hasta" value="{{ request('hasta') }}" class="rounded-md border border-stone-300 px-3 py-2">
        <button class="rounded-md bg-stone-900 px-4 py-2 font-semibold text-white">Filtrar</button>
        <a href="{{ route('inversiones.index') }}" class="rounded-md border border-stone-300 px-4 py-2 font-semibold">Limpiar</a>
    </form>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-stone-500">Total invertido</p>
            <p class="mt-2 text-3xl font-bold text-emerald-800">${{ number_format($totalInversiones, 2) }}</p>
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-stone-500">Registros</p>
            <p class="mt-2 text-3xl font-bold text-stone-900">{{ $inversiones->total() }}</p>
        </div>
    </section>

    <div class="mt-6 overflow-x-auto rounded-lg border border-stone-200 bg-white shadow-sm">
        <table class="w-full min-w-[920px] text-left text-sm">
            <thead class="bg-stone-100 text-stone-600">
                <tr>
                    <th class="p-3">Fecha</th>
                    <th class="p-3">Tipo</th>
                    <th class="p-3">Concepto</th>
                    <th class="p-3">Descripcion</th>
                    <th class="p-3">Monto</th>
                    <th class="p-3">Usuario</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($inversiones as $inversion)
                    <tr>
                        <td class="p-3">{{ $inversion->fecha->format('Y-m-d') }}</td>
                        <td class="p-3">{{ str_replace('_', ' ', ucfirst($inversion->tipo)) }}</td>
                        <td class="p-3 font-semibold text-stone-900">{{ $inversion->concepto }}</td>
                        <td class="p-3">{{ $inversion->descripcion ?: 'Sin descripcion' }}</td>
                        <td class="p-3 font-semibold">${{ number_format($inversion->monto, 2) }}</td>
                        <td class="p-3">{{ $inversion->user?->name ?? 'Sin usuario' }}</td>
                        <td class="p-3">
                            <div class="flex justify-end gap-2">
                                @can('update', $inversion)
                                    <a href="{{ route('inversiones.edit', $inversion) }}" class="rounded-md border border-stone-300 px-3 py-1.5 font-semibold hover:bg-stone-100">Editar</a>
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
                    <tr><td colspan="7" class="p-6 text-center text-stone-500">No hay inversiones registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $inversiones->links() }}</div>
</x-layouts.app>
