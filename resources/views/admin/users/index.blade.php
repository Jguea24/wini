<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Usuarios</h2>
                <p class="mt-1 text-sm text-gray-500">Administracion de accesos y roles.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white">Nuevo usuario</a>
        </div>
    </x-slot>
    <div class="py-8"><div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="w-full min-w-[820px] text-left text-sm">
                <thead class="bg-gray-100 text-gray-600"><tr><th class="p-3">Nombre</th><th class="p-3">Correo</th><th class="p-3">Rol</th><th class="p-3">Estado</th><th class="p-3">Ventas</th><th class="p-3">Gastos</th><th class="p-3"></th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($users as $user)
                        <tr>
                            <td class="p-3 font-semibold">{{ $user->name }}</td><td class="p-3">{{ $user->email }}</td><td class="p-3">{{ ucfirst($user->role) }}</td>
                            <td class="p-3"><span class="rounded-full px-2 py-1 text-xs font-bold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">{{ $user->is_active ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="p-3">{{ $user->ventas_count }}</td><td class="p-3">{{ $user->gastos_count }}</td>
                            <td class="p-3 text-right"><a href="{{ route('admin.users.edit', $user) }}" class="rounded-md border border-gray-300 px-3 py-1.5 font-semibold">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </div></div>
</x-app-layout>
