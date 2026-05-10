<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Configuracion</h2></x-slot>
    <div class="py-8"><div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="grid gap-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            <label class="text-sm font-medium">Nombre de empresa
                <input name="company_name" value="{{ old('company_name', $settings['company_name']) }}" required class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
            </label>
            <label class="text-sm font-medium">Moneda
                <input name="currency" value="{{ old('currency', $settings['currency']) }}" required class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
            </label>
            <label class="text-sm font-medium">Pie de reporte
                <input name="report_footer" value="{{ old('report_footer', $settings['report_footer']) }}" class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
            </label>
            <button class="w-fit rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">Guardar configuracion</button>
        </form>
    </div></div>
</x-app-layout>
