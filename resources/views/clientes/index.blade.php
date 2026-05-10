<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Clientes</h2>
                <p class="mt-1 text-sm text-gray-500">Empresas compradoras y su historial comercial.</p>
            </div>
            <a href="{{ route('clientes.create') }}" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white">Nuevo cliente</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <form class="mb-5 flex gap-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <input name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre, empresa o telefono" class="w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
                <button class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Buscar</button>
            </form>

            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr><th class="p-3">Nombre</th><th class="p-3">Empresa</th><th class="p-3">Telefono</th><th class="p-3">Ventas</th><th class="p-3"></th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($clientes as $cliente)
                            <tr>
                                <td class="p-3 font-semibold">{{ $cliente->nombre }}</td>
                                <td class="p-3">{{ $cliente->empresa ?: 'Sin empresa' }}</td>
                                <td class="p-3">{{ $cliente->telefono ?: 'Sin telefono' }}</td>
                                <td class="p-3">{{ $cliente->ventas_count }}</td>
                                <td class="p-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('clientes.show', $cliente) }}" class="rounded-md border border-gray-300 px-3 py-1.5 font-semibold">Historial</a>
                                        <a href="{{ route('clientes.edit', $cliente) }}" class="rounded-md border border-gray-300 px-3 py-1.5 font-semibold">Editar</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-6 text-center text-gray-500">No hay clientes registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $clientes->links() }}</div>
        </div>
    </div>
</x-app-layout>
