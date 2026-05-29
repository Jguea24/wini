@php($companyName = \App\Models\Setting::getValue('company_name', 'Wini'))

<footer class="mt-auto border-t border-stone-200 bg-stone-950 text-stone-300">
    <div class="mx-auto grid max-w-7xl gap-5 px-4 py-6 sm:grid-cols-[1fr_auto] sm:items-center sm:px-6 lg:px-8">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-amber-900 text-sm font-bold text-white">
                W
            </div>
            <div>
                <p class="text-sm font-semibold text-white">{{ $companyName }}</p>
                <p class="mt-1 max-w-xl text-sm leading-6 text-stone-400">
                    Control financiero de Wini
                </p>
            </div>
        </div>

        <div class="flex flex-col gap-2 text-sm sm:items-end">
            <p class="text-stone-400">&copy; {{ date('Y') }} {{ $companyName }}. Producto sostenible.</p>
            
        </div>
    </div>
</footer>
