<x-layouts.app title="Nueva venta | Wini">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-stone-950">Nueva venta</h1>
            <p class="mt-1 text-sm text-stone-500">Registra ventas de materia prima y datos del cliente.</p>
        </div>
        <a href="{{ route('ventas.index') }}" class="btn-ghost">Volver</a>
    </div>

    <form method="POST" action="{{ route('ventas.store') }}" class="app-card max-w-5xl overflow-hidden">
        @csrf

        <div class="grid gap-6 p-6">
            @include('ventas.partials.form', ['venta' => null])
        </div>

        <div class="flex flex-wrap justify-end gap-3 border-t border-stone-100 bg-stone-50 px-6 py-4">
            <a href="{{ route('ventas.index') }}" class="btn-ghost">Cancelar</a>
            <button class="btn-cacao">Guardar venta</button>
        </div>
    </form>
</x-layouts.app>
