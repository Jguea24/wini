<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Nuevo usuario</h2>
                <p class="mt-1 text-sm text-gray-500">Crea una cuenta con rol, estado y acceso al sistema.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.users.store') }}" class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                @csrf

                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Datos del usuario</h3>
                    <p class="mt-1 text-sm text-gray-500">La contrasena debe tener minimo 8 caracteres.</p>
                </div>

                <div class="p-6">
                    @include('admin.users.partials.form', ['requirePassword' => true])
                </div>

                <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4">
                    <a href="{{ route('admin.users.index') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 font-semibold text-gray-700 hover:bg-gray-50">Cancelar</a>
                    <button class="rounded-md bg-amber-900 px-4 py-2 font-semibold text-white hover:bg-amber-900">Guardar usuario</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
