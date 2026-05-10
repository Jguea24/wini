<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Wini' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50 text-stone-900 antialiased">
    <div class="min-h-screen">
        @auth
            <header class="border-b border-stone-200 bg-white">
                <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-tight text-emerald-800">Wini</a>
                    <nav class="flex flex-wrap items-center gap-2 text-sm font-medium">
                        <a class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-800' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('ventas.*') ? 'bg-emerald-50 text-emerald-800' : '' }}" href="{{ route('ventas.index') }}">Ventas</a>
                        <a class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('gastos.*') ? 'bg-emerald-50 text-emerald-800' : '' }}" href="{{ route('gastos.index') }}">Gastos</a>
                        <a class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('reportes.*') ? 'bg-emerald-50 text-emerald-800' : '' }}" href="{{ route('reportes.index') }}">Reportes</a>
                    </nav>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-stone-600">{{ auth()->user()->name }} · {{ ucfirst(auth()->user()->role) }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-md bg-stone-900 px-3 py-2 font-semibold text-white hover:bg-stone-700">Salir</button>
                        </form>
                    </div>
                </div>
            </header>
        @endauth

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ session('status') }}</div>
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
    </div>
</body>
</html>
