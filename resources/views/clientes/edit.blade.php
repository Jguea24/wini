<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Editar cliente</h2></x-slot>
    <div class="py-8"><div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('clientes.update', $cliente) }}" class="grid gap-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            @include('clientes.partials.form')
            <div class="flex gap-3"><button class="rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">Actualizar</button><a href="{{ route('clientes.index') }}" class="rounded-md border border-gray-300 px-4 py-2 font-semibold">Cancelar</a></div>
        </form>
    </div></div>
</x-app-layout>
