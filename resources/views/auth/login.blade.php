<x-guest-layout>
    <main class="relative flex min-h-full items-center justify-center overflow-hidden bg-stone-100 px-4 py-6 sm:px-6">
        <div class="absolute inset-0 bg-[linear-gradient(60deg,rgba(120,53,15,.09)_0_18%,transparent_18%_32%,rgba(15,23,42,.08)_32%_46%,transparent_46%_62%,rgba(146,64,14,.08)_62%_78%,transparent_78%)]"></div>

        <div class="relative w-full max-w-5xl overflow-hidden rounded-2xl bg-white shadow-2xl lg:grid lg:grid-cols-[.95fr_1.05fr]">
            <section class="flex min-h-[280px] flex-col items-center justify-center bg-stone-900 px-8 py-10 text-center text-white lg:min-h-[500px]">
                <a href="{{ route('login') }}" class="flex flex-col items-center">
                    @if (file_exists(public_path('images/wini-logo.png')))
                        <img src="{{ asset('images/wini-logo.png') }}" alt="Wini" class="h-44 w-auto object-contain sm:h-56">
                    @else
                        <div class="flex h-32 w-32 items-center justify-center rounded-full border border-white/40 text-4xl font-bold">W</div>
                    @endif
                    <p class="mt-6 text-3xl font-bold tracking-tight">Wini</p>
                    <p class="mt-2 text-base text-amber-100">{{ __('wini.brand_tagline') }}</p>
                </a>
            </section>

            <section class="relative flex items-center justify-center px-6 py-10 sm:px-12">
                <div class="absolute right-6 top-5">
                    <x-language-switcher />
                </div>

                <div class="w-full max-w-sm pt-8">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold tracking-tight text-stone-950">{{ __('wini.login_title') }}</h2>
                        <p class="mt-2 text-sm text-stone-600">{{ __('wini.login_subtitle') }}</p>
                    </div>

                    <x-auth-session-status class="mb-5" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div>
                            <div class="floating-control">
                                <x-input-label for="email" :value="__('wini.email')" class="floating-label" />
                                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mt-5">
                            <div class="floating-control">
                                <x-input-label for="password" :value="__('wini.password')" class="floating-label" />
                                <x-password-input id="password" name="password" required autocomplete="current-password" placeholder="" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mt-5 flex items-center justify-between gap-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-stone-300 text-amber-900 shadow-sm focus:ring-amber-800" name="remember">
                                <span class="ms-2 text-sm text-stone-600">{{ __('wini.remember_me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm font-semibold text-amber-900 hover:text-amber-950" href="{{ route('password.request') }}">
                                    {{ __('wini.forgot_password') }}
                                </a>
                            @endif
                        </div>

                        <button class="mt-6 w-full rounded-md bg-amber-900 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-amber-950/20 transition hover:bg-stone-950 focus:outline-none focus:ring-2 focus:ring-amber-800 focus:ring-offset-2">
                            {{ __('wini.login_button') }}
                        </button>
                    </form>

                    @if (Route::has('register'))
                        <p class="mt-8 text-center text-sm text-stone-600">
                            {{ __('wini.no_account') }}
                            <a href="{{ route('register') }}" class="font-semibold text-amber-900 hover:text-amber-950">{{ __('wini.create_account') }}</a>
                        </p>
                    @endif
                </div>
            </section>
        </div>
    </main>
</x-guest-layout>
