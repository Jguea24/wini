<footer class="mt-auto border-t border-stone-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-5 text-sm text-stone-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::getValue('company_name', 'Wini') }}. Producto sostenible.</p>
        <p>Control financiero, ventas, gastos, inversiones y facturación.</p>
    </div>
</footer>
