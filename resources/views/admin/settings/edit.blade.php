<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Configuracion</h2></x-slot>
    <div class="py-8"><div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="grid gap-5 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            <section>
                <h3 class="text-lg font-semibold text-gray-900">Datos legales de la empresa</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <label class="text-sm font-medium">Nombre de empresa
                        <input name="company_name" value="{{ old('company_name', $settings['company_name']) }}" required class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </label>
                    <label class="text-sm font-medium">RUC/Cédula
                        <input name="company_ruc" value="{{ old('company_ruc', $settings['company_ruc']) }}" class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </label>
                    <label class="text-sm font-medium">Teléfono
                        <input name="company_phone" value="{{ old('company_phone', $settings['company_phone']) }}" class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </label>
                    <label class="text-sm font-medium">Correo
                        <input type="email" name="company_email" value="{{ old('company_email', $settings['company_email']) }}" class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </label>
                    <label class="text-sm font-medium sm:col-span-2">Dirección
                        <input name="company_address" value="{{ old('company_address', $settings['company_address']) }}" class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </label>
                </div>
            </section>

            <section class="border-t border-gray-200 pt-5">
                <h3 class="text-lg font-semibold text-gray-900">Facturación</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    <label class="text-sm font-medium">Prefijo
                        <input name="invoice_prefix" value="{{ old('invoice_prefix', $settings['invoice_prefix']) }}" required class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </label>
                    <label class="text-sm font-medium">Siguiente número
                        <input type="number" name="invoice_next_number" value="{{ old('invoice_next_number', $settings['invoice_next_number']) }}" min="1" required class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </label>
                    <label class="text-sm font-medium">Impuesto %
                        <input type="number" step="0.01" name="invoice_tax_rate" value="{{ old('invoice_tax_rate', $settings['invoice_tax_rate']) }}" min="0" max="100" required class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </label>
                </div>
            </section>

            <section class="border-t border-gray-200 pt-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="text-sm font-medium">Moneda
                        <input name="currency" value="{{ old('currency', $settings['currency']) }}" required class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </label>
                    <label class="text-sm font-medium">Pie de reporte
                        <input name="report_footer" value="{{ old('report_footer', $settings['report_footer']) }}" class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </label>
                </div>
            </section>
            <button class="w-fit rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">Guardar configuración</button>
        </form>
    </div></div>
</x-app-layout>
