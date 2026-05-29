<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Usuarios</h2>
                <p class="mt-1 text-sm text-gray-500">Administracion de accesos y roles.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="rounded-md bg-amber-900 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-900">Nuevo usuario</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-900">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Cuentas registradas</h3>
                    <p class="mt-1 text-sm text-gray-500">Gestiona permisos, estado y actividad de cada usuario.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[860px] text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Usuario</th>
                                <th class="px-5 py-3">Rol</th>
                                <th class="px-5 py-3">Estado</th>
                                <th class="px-5 py-3">Ventas</th>
                                <th class="px-5 py-3">Gastos</th>
                                <th class="px-5 py-3">Creado</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($user->profile_photo_path)
                                                <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-900 text-sm font-bold text-white">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                                <p class="mt-0.5 text-gray-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700">{{ ucfirst($user->role) }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $user->is_active ? 'bg-amber-50 text-amber-900' : 'bg-red-50 text-red-700' }}">
                                            {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-gray-900">{{ $user->ventas_count }}</td>
                                    <td class="px-5 py-4 font-semibold text-gray-900">{{ $user->gastos_count }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $user->created_at?->format('Y-m-d') }}</td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="rounded-md border border-gray-300 bg-white px-3 py-1.5 font-semibold text-gray-700 hover:bg-gray-50">Editar</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center text-gray-500">No hay usuarios registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>
