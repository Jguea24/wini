<x-layouts.app title="Editar gasto | Wini">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-stone-950">Editar gasto</h1>
            <p class="mt-1 text-sm text-stone-500">Actualiza la fecha, tipo, descripcion o monto.</p>
        </div>
        <a href="{{ route('gastos.index') }}" class="btn-ghost">Volver</a>
    </div>

    <form method="POST" action="{{ route('gastos.update', $gasto) }}" class="app-card max-w-4xl overflow-hidden">
        @csrf
        @method('PUT')

        <div class="grid gap-6 p-6">
            @include('gastos.partials.form', ['gasto' => $gasto])
        </div>

        <div class="flex flex-wrap justify-end gap-3 border-t border-stone-100 bg-stone-50 px-6 py-4">
            <a href="{{ route('gastos.index') }}" class="btn-ghost">Cancelar</a>
            <button class="btn-cacao">Actualizar gasto</button>
        </div>
    </form>
</x-layouts.app>
