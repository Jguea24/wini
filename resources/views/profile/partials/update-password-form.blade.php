<section>
    <header class="border-b border-gray-100 pb-4">
        <h3 class="text-lg font-semibold text-gray-900">Actualizar contrasena</h3>
        <p class="mt-1 text-sm text-gray-600">Usa una contrasena larga y segura para proteger la cuenta.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-5 grid gap-5">
        @csrf
        @method('put')

        <div class="grid gap-5">
            <div class="text-sm font-medium text-gray-700">
                <div class="floating-control mt-1">
                    <span class="floating-label">Contrasena actual</span>
                    <x-password-input id="update_password_current_password" name="current_password" autocomplete="current-password" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div class="text-sm font-medium text-gray-700">
                <div class="floating-control mt-1">
                    <span class="floating-label">Nueva contrasena</span>
                    <x-password-input id="update_password_password" name="password" autocomplete="new-password" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div class="text-sm font-medium text-gray-700">
                <div class="floating-control mt-1">
                    <span class="floating-label">Confirmar contrasena</span>
                    <x-password-input id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <button class="rounded-md bg-amber-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-amber-900">Actualizar contrasena</button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-medium text-amber-900">Contrasena actualizada.</p>
            @endif
        </div>
    </form>
</section>
