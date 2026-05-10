<x-guest-layout>
    <main class="grid min-h-screen lg:grid-cols-[1.05fr_.95fr]">
        <section class="relative hidden overflow-hidden bg-stone-950 lg:block">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_25%_20%,rgba(16,185,129,.22),transparent_30%),radial-gradient(circle_at_75%_65%,rgba(180,83,9,.24),transparent_30%)]"></div>
            <div class="relative flex h-full flex-col items-center justify-center px-12 py-10 text-center text-white">
                <a href="{{ route('login') }}" class="flex flex-col items-center gap-6">
                    @if (file_exists(public_path('images/wini-logo.png')))
                        <img src="{{ asset('images/wini-logo.png') }}" alt="Wini" class="h-80 w-auto object-contain xl:h-[28rem] 2xl:h-[34rem]">
                    @else
                        <div class="flex h-56 w-56 items-center justify-center rounded-lg bg-emerald-700 text-7xl font-bold">W</div>
                    @endif
                    <div>
                        <p class="text-5xl font-bold tracking-tight">Wini</p>
                        <p class="mt-2 text-lg text-emerald-100">{{ __('wini.brand_tagline') }}</p>
                    </div>
                </a>
            </div>
        </section>

        <section class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-10">
            <div class="w-full max-w-md">
                <div class="mb-4 flex justify-end">
                    <x-language-switcher />
                </div>
                <div class="mb-8 text-center lg:hidden">
                    @if (file_exists(public_path('images/wini-logo.png')))
                        <img src="{{ asset('images/wini-logo.png') }}" alt="Wini" class="mx-auto h-36 w-auto object-contain sm:h-44">
                    @else
                        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-lg bg-emerald-700 text-3xl font-bold text-white">W</div>
                    @endif
                    <h1 class="mt-3 text-3xl font-bold text-stone-950">Wini</h1>
                </div>

                <div class="rounded-lg border border-stone-200 bg-white p-8 shadow-sm">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight text-stone-950">{{ __('wini.register_title') }}</h2>
                        <p class="mt-2 text-sm text-stone-600">{{ __('wini.register_subtitle') }}</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="mt-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('wini.name')" />
                            <x-text-input id="name" class="mt-1 block w-full rounded-md border-stone-300 focus:border-emerald-600 focus:ring-emerald-600" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" :placeholder="__('wini.full_name')" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-5">
                            <x-input-label for="email" :value="__('wini.email')" />
                            <x-text-input id="email" class="mt-1 block w-full rounded-md border-stone-300 focus:border-emerald-600 focus:ring-emerald-600" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="usuario@wini.local" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mt-5">
                            <x-input-label for="password" :value="__('wini.password')" />
                            <x-text-input id="password" class="mt-1 block w-full rounded-md border-stone-300 focus:border-emerald-600 focus:ring-emerald-600" type="password" name="password" required autocomplete="new-password" placeholder="Minimo 8 caracteres" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mt-5">
                            <x-input-label for="password_confirmation" :value="__('wini.confirm_password')" />
                            <x-text-input id="password_confirmation" class="mt-1 block w-full rounded-md border-stone-300 focus:border-emerald-600 focus:ring-emerald-600" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repite la contrasena" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <button class="mt-6 w-full rounded-md bg-emerald-700 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                            {{ __('wini.create_account') }}
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm text-stone-600">
                        {{ __('wini.already_registered') }}
                        <a href="{{ route('login') }}" class="font-semibold text-emerald-700 hover:text-emerald-900">{{ __('wini.login_button') }}</a>
                    </p>
                </div>
            </div>
        </section>
    </main>
</x-guest-layout>
