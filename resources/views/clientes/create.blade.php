<x-app-layout>
    <x-slot name="header">
        <div class="mx-auto max-w-4xl">
            <nav class="flex items-center gap-2 text-sm text-stone-500" aria-label="Breadcrumb">
                <a href="{{ route('clientes.index') }}" class="transition-colors hover:text-[#6F4E37]">Clientes</a>
                <svg class="h-4 w-4 text-stone-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium text-stone-900">Nuevo cliente</span>
            </nav>
        </div>
    </x-slot>

    @include('clientes.partials.premium-form', [
        'action' => route('clientes.store'),
        'title' => 'Nuevo Cliente',
        'description' => 'Registre la informacion comercial del cliente.',
        'submitLabel' => 'Guardar',
        'iconPath' => 'M18 7.5v3m0 0v3m0-3h3m-3 0h-3M12 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM2.25 20.25a8.25 8.25 0 0 1 15 0',
    ])
</x-app-layout>
