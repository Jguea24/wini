<section>
    <header class="border-b border-gray-100 pb-4">
        <h3 class="text-lg font-semibold text-gray-900">Informacion del perfil</h3>
        <p class="mt-1 text-sm text-gray-600">Actualiza tu nombre y correo electronico.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-5 grid gap-5">
        @csrf
        @method('patch')

        <div class="grid gap-5">
            <label class="text-sm font-medium text-gray-700">Nombre
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </label>

            <label class="text-sm font-medium text-gray-700">Correo electronico
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </label>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="rounded-md bg-amber-50 p-4 text-sm text-amber-900">
                Tu correo no esta verificado.
                <button form="send-verification" class="font-semibold underline">Reenviar enlace de verificacion</button>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-emerald-700">Se envio un nuevo enlace de verificacion.</p>
                @endif
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-4">
            <button class="rounded-md bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-800">Guardar cambios</button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-medium text-emerald-700">Guardado.</p>
            @endif
        </div>
    </form>
</section>
