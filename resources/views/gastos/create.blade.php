<x-layouts.app title="Nuevo gasto | Wini">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-stone-950">Nuevo gasto</h1>
            <p class="mt-1 text-sm text-stone-500">Registra un costo operativo del negocio.</p>
        </div>
        <a href="{{ route('gastos.index') }}" class="btn-ghost">Volver</a>
    </div>

    <form method="POST" action="{{ route('gastos.store') }}" class="app-card max-w-4xl overflow-hidden">
        @csrf

        <div class="grid gap-6 p-6">
            @include('gastos.partials.form', ['gasto' => null])
        </div>

        <div class="flex flex-wrap justify-end gap-3 border-t border-stone-100 bg-stone-50 px-6 py-4">
            <a href="{{ route('gastos.index') }}" class="btn-ghost">Cancelar</a>
            <button class="btn-cacao">Guardar gasto</button>
        </div>
    </form>
</x-layouts.app>
