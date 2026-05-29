<section class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <h3 class="text-lg font-semibold text-red-700">Eliminar cuenta</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-600">
            Esta accion elimina permanentemente tu usuario. Antes de continuar, verifica que no necesites conservar acceso a tus registros.
        </p>
    </div>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="w-fit rounded-md bg-red-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-red-800">
        Eliminar cuenta
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-gray-900">Confirmar eliminacion</h2>
            <p class="mt-2 text-sm text-gray-600">Ingresa tu contrasena para eliminar la cuenta de forma permanente.</p>

            <div class="mt-6">
                <x-input-label for="password" value="Contrasena" class="sr-only" />
                <div class="floating-control mt-1">
                    <span class="floating-label">Contrasena</span>
                    <x-password-input id="password" name="password" placeholder="Contrasena" />
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <button class="rounded-md bg-red-700 px-4 py-2 text-sm font-bold text-white hover:bg-red-800">Eliminar cuenta</button>
            </div>
        </form>
    </x-modal>
</section>
