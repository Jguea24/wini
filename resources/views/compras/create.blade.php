<x-layouts.app title="Nueva compra | Wini">
    <h1 class="text-3xl font-bold tracking-tight">Nueva compra</h1>
    <form method="POST" action="{{ route('compras.store') }}" class="mt-6 grid max-w-2xl gap-4 rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
        @csrf
        <label class="text-sm font-medium">Fecha <input type="date" name="fecha" value="{{ old('fecha', now()->toDateString()) }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2"></label>
        <label class="text-sm font-medium">Proveedor <input name="proveedor" value="{{ old('proveedor') }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2"></label>
        <label class="text-sm font-medium">Libras compradas <input type="number" step="0.01" min="0.01" name="libras" value="{{ old('libras') }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2"></label>
        <label class="text-sm font-medium">Precio por libra <input type="number" step="0.01" min="0" name="precio_libra" value="{{ old('precio_libra') }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2"></label>
        <div class="flex gap-3"><button class="rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">Guardar</button><a href="{{ route('compras.index') }}" class="rounded-md border border-stone-300 px-4 py-2 font-semibold">Cancelar</a></div>
    </form>
</x-layouts.app>
