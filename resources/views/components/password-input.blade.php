@props(['disabled' => false])

<div x-data="{ show: false }" class="relative">
    <input
        @disabled($disabled)
        {{ $attributes->merge(['class' => 'block h-11 w-full rounded-lg border border-stone-700 bg-white px-3 pr-11 text-sm text-stone-900 shadow-none focus:border-amber-900 focus:ring-1 focus:ring-amber-900']) }}
        :type="show ? 'text' : 'password'"
    >

    <button
        type="button"
        class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-stone-500 transition hover:text-stone-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-amber-800"
        :aria-label="show ? 'Ocultar contrasena' : 'Mostrar contrasena'"
        @click="show = ! show"
    >
        <svg x-show="! show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m2 2 20 20" />
            <path d="M6.7 6.7C3.7 8.7 2 12 2 12s3.6 7 10 7c1.8 0 3.4-.5 4.7-1.2" />
            <path d="M19.6 14.6C21.1 13.3 22 12 22 12s-3.6-7-10-7c-.9 0-1.8.1-2.6.4" />
            <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" />
        </svg>

        <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" />
            <circle cx="12" cy="12" r="3" />
        </svg>
    </button>
</div>
