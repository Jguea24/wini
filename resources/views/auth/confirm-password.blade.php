<x-guest-layout>
    <main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
        <section class="w-full max-w-md">
            <div class="mb-4 flex justify-end">
                <x-language-switcher />
            </div>
            <div class="mb-8 text-center">
                @if (file_exists(public_path('images/wini-logo.png')))
                    <img src="{{ asset('images/wini-logo.png') }}" alt="Wini" class="mx-auto h-36 w-auto object-contain sm:h-44">
                @else
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-lg bg-emerald-700 text-3xl font-bold text-white">W</div>
                @endif
            </div>

            <div class="rounded-lg border border-stone-200 bg-white p-8 shadow-sm">
                <div class="text-center">
                    <h1 class="text-2xl font-bold tracking-tight text-stone-950">{{ __('wini.confirm_access_title') }}</h1>
                    <p class="mt-3 text-sm leading-6 text-stone-600">{{ __('wini.confirm_access_subtitle') }}</p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" class="mt-6">
                    @csrf

                    <div>
                        <x-input-label for="password" :value="__('wini.password')" />
                        <x-text-input id="password" class="mt-1 block w-full rounded-md border-stone-300 focus:border-emerald-600 focus:ring-emerald-600" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <button class="mt-6 w-full rounded-md bg-emerald-700 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                        {{ __('wini.confirm') }}
                    </button>
                </form>
            </div>
        </section>
    </main>
</x-guest-layout>
