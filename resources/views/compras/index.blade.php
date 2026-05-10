<x-layouts.app title="Compras | Wini">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Compras</h1>
            <p class="mt-1 text-stone-600">Historial de cacao comprado por proveedor.</p>
        </div>
        <a href="{{ route('compras.create') }}" class="rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800">Nueva compra</a>
    </div>
    <form class="mt-6 flex flex-wrap gap-3 rounded-lg border border-stone-200 bg-white p-4 shadow-sm">
        <input type="date" name="desde" value="{{ request('desde') }}" class="rounded-md border border-stone-300 px-3 py-2">
        <input type="date" name="hasta" value="{{ request('hasta') }}" class="rounded-md border border-stone-300 px-3 py-2">
        <button class="rounded-md bg-stone-900 px-4 py-2 font-semibold text-white">Filtrar</button>
        <a href="{{ route('compras.index') }}" class="rounded-md border border-stone-300 px-4 py-2 font-semibold">Limpiar</a>
    </form>
    <div class="mt-6 overflow-hidden rounded-lg border border-stone-200 bg-white shadow-sm">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead class="bg-stone-100 text-stone-600">
                <tr><th class="p-3">Fecha</th><th class="p-3">Proveedor</th><th class="p-3">Libras</th><th class="p-3">Precio/lb</th><th class="p-3">Total</th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($compras as $compra)
                    <tr><td class="p-3">{{ $compra->fecha->format('Y-m-d') }}</td><td class="p-3">{{ $compra->proveedor->nombre }}</td><td class="p-3">{{ number_format($compra->libras, 2) }}</td><td class="p-3">${{ number_format($compra->precio_libra, 2) }}</td><td class="p-3 font-semibold">${{ number_format($compra->total_pagado, 2) }}</td></tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-stone-500">No hay compras registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $compras->links() }}</div>
</x-layouts.app>
