<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Editar usuario</h2>
                <p class="mt-1 text-sm text-gray-500">Actualiza los datos de {{ $user->name }}.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-4 border-b border-gray-100 p-6 sm:flex-row sm:items-center">
                    @if ($user->profile_photo_path)
                        <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-14 w-14 rounded-full object-cover">
                    @else
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-amber-900 text-xl font-bold text-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="p-6">
                    @include('admin.users.partials.form', ['requirePassword' => false])
                    <p class="mt-4 text-sm text-gray-500">Deja la contrasena vacia si no quieres cambiarla.</p>
                </div>

                <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4">
                    <a href="{{ route('admin.users.index') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 font-semibold text-gray-700 hover:bg-gray-50">Cancelar</a>
                    <button class="rounded-md bg-amber-900 px-4 py-2 font-semibold text-white hover:bg-amber-900">Actualizar usuario</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
