<section>
    <header class="border-b border-gray-100 pb-4">
        <h3 class="text-lg font-semibold text-gray-900">Informacion del perfil</h3>
        <p class="mt-1 text-sm text-gray-600">Actualiza tu nombre, correo electronico y foto.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-5 grid gap-5" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid gap-5">
            <div class="flex flex-col gap-4 rounded-md border border-gray-100 bg-gray-50 p-4 sm:flex-row sm:items-center">
                @if ($user->profile_photo_path)
                    <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-16 w-16 shrink-0 rounded-full object-cover">
                @else
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-amber-900 text-2xl font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div class="min-w-0 flex-1">
                    <label for="profile_photo" class="block text-sm font-medium text-gray-700">Foto de perfil</label>
                    <input id="profile_photo" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-amber-900 file:px-4 file:py-2 file:text-sm file:font-bold file:text-white hover:file:bg-amber-900">
                    <p class="mt-1 text-xs text-gray-500">JPG, PNG o WEBP. Maximo 2 MB.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                </div>
            </div>

            <label class="text-sm font-medium text-gray-700">Nombre
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 focus:border-amber-800 focus:ring-amber-800" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </label>

            <label class="text-sm font-medium text-gray-700">Correo electronico
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 focus:border-amber-800 focus:ring-amber-800" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </label>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="rounded-md bg-amber-50 p-4 text-sm text-amber-900">
                Tu correo no esta verificado.
                <button form="send-verification" class="font-semibold underline">Reenviar enlace de verificacion</button>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-amber-900">Se envio un nuevo enlace de verificacion.</p>
                @endif
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-4">
            <button class="rounded-md bg-amber-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-amber-900">Guardar cambios</button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-medium text-amber-900">Guardado.</p>
            @endif
        </div>
    </form>
</section>
