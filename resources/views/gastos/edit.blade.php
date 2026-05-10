<x-layouts.app title="Editar gasto | Wini">
    <h1 class="text-3xl font-bold tracking-tight">Editar gasto</h1>
    <form method="POST" action="{{ route('gastos.update', $gasto) }}" class="mt-6 grid max-w-2xl gap-4 rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')
        @include('gastos.partials.form', ['gasto' => $gasto])
        <div class="flex gap-3">
            <button class="rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">Actualizar</button>
            <a href="{{ route('gastos.index') }}" class="rounded-md border border-stone-300 px-4 py-2 font-semibold">Cancelar</a>
        </div>
    </form>
</x-layouts.app>
