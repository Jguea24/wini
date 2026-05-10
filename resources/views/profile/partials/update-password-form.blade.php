<section>
    <header class="border-b border-gray-100 pb-4">
        <h3 class="text-lg font-semibold text-gray-900">Actualizar contrasena</h3>
        <p class="mt-1 text-sm text-gray-600">Usa una contrasena larga y segura para proteger la cuenta.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-5 grid gap-5">
        @csrf
        @method('put')

        <div class="grid gap-5">
            <label class="text-sm font-medium text-gray-700">Contrasena actual
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </label>

            <label class="text-sm font-medium text-gray-700">Nueva contrasena
                <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </label>

            <label class="text-sm font-medium text-gray-700">Confirmar contrasena
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </label>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <button class="rounded-md bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-800">Actualizar contrasena</button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-medium text-emerald-700">Contrasena actualizada.</p>
            @endif
        </div>
    </form>
</section>
