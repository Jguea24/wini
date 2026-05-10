<x-layouts.app title="Nueva venta | Wini">
    <h1 class="text-3xl font-bold tracking-tight">Nueva venta</h1>
    <form method="POST" action="{{ route('ventas.store') }}" class="mt-6 grid max-w-3xl gap-4 rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
        @csrf
        @include('ventas.partials.form', ['venta' => null])
        <div class="flex gap-3">
            <button class="rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">Guardar</button>
            <a href="{{ route('ventas.index') }}" class="rounded-md border border-stone-300 px-4 py-2 font-semibold">Cancelar</a>
        </div>
    </form>
</x-layouts.app>
