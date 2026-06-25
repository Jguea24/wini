<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Wini' }}</title>
    <x-pwa-head />
    @vite('resources/js/app.js')
</head>
<body class="bg-stone-50 text-stone-900 antialiased">
    @php($navUser = auth()->user())

    <div class="flex min-h-screen flex-col">
        @auth
            <header class="border-b border-stone-200 bg-white">
                <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-tight text-amber-900">Wini</a>
                    <nav class="flex flex-wrap items-center gap-2 text-sm font-medium">
                        <a class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('dashboard') ? 'bg-amber-50 text-amber-900' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('ventas.*') ? 'bg-amber-50 text-amber-900' : '' }}" href="{{ route('ventas.index') }}">Ventas</a>
                        <a class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('clientes.*') ? 'bg-amber-50 text-amber-900' : '' }}" href="{{ route('clientes.index') }}">Clientes</a>
                        <a class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('gastos.*') ? 'bg-amber-50 text-amber-900' : '' }}" href="{{ route('gastos.index') }}">Gastos</a>
                        <a class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('inversiones.*') ? 'bg-amber-50 text-amber-900' : '' }}" href="{{ route('inversiones.index') }}">Inversiones</a>
                        <a class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('facturas.*') ? 'bg-amber-50 text-amber-900' : '' }}" href="{{ route('facturas.index') }}">Facturas</a>
                        <a class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('reportes.*') ? 'bg-amber-50 text-amber-900' : '' }}" href="{{ route('reportes.index') }}">Reportes</a>
                    </nav>
                    <div class="flex items-center gap-5">
                        <x-language-switcher />
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2 rounded-full bg-white text-sm font-medium text-slate-600 transition hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-800 focus:ring-offset-2">
                                    @if ($navUser?->profile_photo_path)
                                        <img src="{{ asset('storage/'.$navUser->profile_photo_path) }}" alt="{{ $navUser->name }}" class="h-9 w-9 rounded-full object-cover ring-2 ring-white">
                                    @else
                                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-900 text-xs font-bold text-white ring-2 ring-white">
                                            {{ strtoupper(substr($navUser?->name ?? 'W', 0, 1)) }}
                                        </span>
                                    @endif

                                    <span class="max-w-36 truncate">{{ $navUser?->name }}</span>

                                    <span class="text-slate-400">
                                        <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('wini.profile') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('wini.logout') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </header>
        @endauth

        <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-8 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-950">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900">
                    <p class="font-semibold">Revisa los datos ingresados.</p>
                    <ul class="mt-2 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </main>
        <x-app-footer />
    </div>
</body>
</html>
