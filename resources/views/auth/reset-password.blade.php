<x-guest-layout>
    <main class="flex min-h-full items-center justify-center px-4 py-6 sm:px-6">
        <section class="w-full max-w-md">
            <div class="mb-4 flex justify-end">
                <x-language-switcher />
            </div>
            <div class="mb-8 text-center">
                @if (file_exists(public_path('images/wini-logo.png')))
                    <img src="{{ asset('images/wini-logo.png') }}" alt="Wini" class="mx-auto h-36 w-auto object-contain sm:h-44">
                @else
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-lg bg-amber-900 text-3xl font-bold text-white">W</div>
                @endif
            </div>

            <div class="rounded-lg border border-stone-200 bg-white p-8 shadow-sm">
                <div class="text-center">
                    <h1 class="text-2xl font-bold tracking-tight text-stone-950">{{ __('wini.new_password_title') }}</h1>
                    <p class="mt-3 text-sm leading-6 text-stone-600">{{ __('wini.new_password_subtitle') }}</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="mt-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <div class="floating-control">
                            <x-input-label for="email" :value="__('wini.email')" class="floating-label" />
                            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-5">
                        <div class="floating-control">
                            <x-input-label for="password" :value="__('wini.password')" class="floating-label" />
                            <x-password-input id="password" name="password" required autocomplete="new-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-5">
                        <div class="floating-control">
                            <x-input-label for="password_confirmation" :value="__('wini.confirm_password')" class="floating-label" />
                            <x-password-input id="password_confirmation" name="password_confirmation" required autocomplete="new-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button class="mt-6 w-full rounded-md bg-amber-900 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-stone-950 focus:outline-none focus:ring-2 focus:ring-amber-800 focus:ring-offset-2">
                        {{ __('wini.save_new_password') }}
                    </button>
                </form>
            </div>
        </section>
    </main>
</x-guest-layout>
