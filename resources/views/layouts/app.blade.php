<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Wini') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('images/wini-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/wini-logo.png') }}">
        <x-pwa-head />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite('resources/js/app.js')
    </head>
    <body class="font-sans antialiased">
        <div
            x-data="{ showLoginSuccess: {{ session('login_success') ? 'true' : 'false' }} }"
            class="flex min-h-screen flex-col bg-stone-100"
        >
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="border-b border-stone-200 bg-white shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
            <x-app-footer />

            <div
                x-cloak
                x-show="showLoginSuccess"
                x-transition.opacity.duration.200ms
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
                role="dialog"
                aria-modal="true"
                aria-labelledby="login-success-title"
            >
                <div
                    x-show="showLoginSuccess"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="scale-95 opacity-0"
                    x-transition:enter-end="scale-100 opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="scale-100 opacity-100"
                    x-transition:leave-end="scale-95 opacity-0"
                    @click.outside="showLoginSuccess = false"
                    class="w-full max-w-xs rounded-md bg-white px-8 py-7 text-center shadow-2xl"
                >
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full border-2 border-emerald-500 text-emerald-500">
                        <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                        </svg>
                    </div>

                    <h2 id="login-success-title" class="mt-5 text-lg font-bold text-stone-700">Ingreso exitoso</h2>
                    <p class="mt-2 text-sm text-stone-500">Bienvenido al sistema Wini.</p>

                    <button
                        type="button"
                        @click="showLoginSuccess = false"
                        class="mt-5 rounded bg-emerald-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </body>
</html>
