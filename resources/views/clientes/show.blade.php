<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $cliente->nombre_comercial }}</h2>
                <p class="mt-1 text-sm text-gray-500">Historial de ventas del cliente.</p>
            </div>
            <a href="{{ route('clientes.index') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold">Volver</a>
        </div>
    </x-slot>
    <div class="py-8"><div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="bg-gray-100 text-gray-600"><tr><th class="p-3">Fecha</th><th class="p-3">Libras</th><th class="p-3">Precio/lb</th><th class="p-3">Total</th><th class="p-3">Usuario</th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($ventas as $venta)
                        <tr><td class="p-3">{{ $venta->fecha->format('Y-m-d') }}</td><td class="p-3">{{ number_format($venta->libras, 2) }}</td><td class="p-3">${{ number_format($venta->precio_por_libra, 2) }}</td><td class="p-3 font-semibold">${{ number_format($venta->total, 2) }}</td><td class="p-3">{{ $venta->user?->name }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="p-6 text-center text-gray-500">Este cliente no tiene ventas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $ventas->links() }}</div>
    </div></div>
</x-app-layout>
