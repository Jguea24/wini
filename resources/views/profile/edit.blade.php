<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Mi perfil</h2>
                <p class="mt-1 text-sm text-gray-500">Administra tus datos personales, seguridad y acceso.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Volver al dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-col gap-6 p-6 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-3xl font-bold text-white shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $user->name }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3 md:min-w-[420px]">
                        <div class="rounded-md bg-gray-50 p-3">
                            <p class="text-xs font-medium uppercase text-gray-500">Rol</p>
                            <p class="mt-1 font-bold text-gray-900">{{ ucfirst($user->role) }}</p>
                        </div>
                        <div class="rounded-md bg-emerald-50 p-3">
                            <p class="text-xs font-medium uppercase text-emerald-700">Estado</p>
                            <p class="mt-1 font-bold text-emerald-900">{{ $user->is_active ? 'Activo' : 'Inactivo' }}</p>
                        </div>
                        <div class="rounded-md bg-gray-50 p-3">
                            <p class="text-xs font-medium uppercase text-gray-500">Creada</p>
                            <p class="mt-1 font-bold text-gray-900">{{ $user->created_at?->format('Y-m-d') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

            <section class="rounded-lg border border-red-200 bg-white p-6 shadow-sm">
                @include('profile.partials.delete-user-form')
            </section>
        </div>
    </div>
</x-app-layout>
