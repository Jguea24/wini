<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Configuracion</h2>
                <p class="mt-1 text-sm text-gray-500">Datos legales, facturacion y parametros generales de Wini.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Volver al dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-900">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                @csrf
                @method('PUT')

                <div class="border-b border-gray-100 p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $settings['company_name'] ?: 'Wini' }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $settings['company_email'] ?: 'Sin correo configurado' }}</p>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-md bg-amber-50 px-4 py-3">
                                <p class="text-xs font-medium uppercase text-amber-900">Prefijo</p>
                                <p class="mt-1 font-bold text-amber-950">{{ $settings['invoice_prefix'] }}</p>
                            </div>
                            <div class="rounded-md bg-gray-50 px-4 py-3">
                                <p class="text-xs font-medium uppercase text-gray-500">Siguiente factura</p>
                                <p class="mt-1 font-bold text-gray-900">{{ $settings['invoice_next_number'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-8 p-6">
                    <section>
                        <div class="mb-5">
                            <h3 class="text-lg font-semibold text-gray-900">Datos legales de la empresa</h3>
                            <p class="mt-1 text-sm text-gray-500">Estos datos aparecen en facturas y reportes.</p>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="floating-control">
                                <label for="company_name" class="floating-label">Nombre de empresa</label>
                                <input id="company_name" name="company_name" value="{{ old('company_name', $settings['company_name']) }}" required>
                                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                            </div>

                            <div class="floating-control">
                                <label for="company_ruc" class="floating-label">RUC/Cedula</label>
                                <input id="company_ruc" name="company_ruc" value="{{ old('company_ruc', $settings['company_ruc']) }}">
                                <x-input-error :messages="$errors->get('company_ruc')" class="mt-2" />
                            </div>

                            <div class="floating-control">
                                <label for="company_phone" class="floating-label">Telefono</label>
                                <input id="company_phone" name="company_phone" value="{{ old('company_phone', $settings['company_phone']) }}">
                                <x-input-error :messages="$errors->get('company_phone')" class="mt-2" />
                            </div>

                            <div class="floating-control">
                                <label for="company_email" class="floating-label">Correo</label>
                                <input id="company_email" type="email" name="company_email" value="{{ old('company_email', $settings['company_email']) }}">
                                <x-input-error :messages="$errors->get('company_email')" class="mt-2" />
                            </div>

                            <div class="floating-control sm:col-span-2">
                                <label for="company_address" class="floating-label">Direccion</label>
                                <input id="company_address" name="company_address" value="{{ old('company_address', $settings['company_address']) }}">
                                <x-input-error :messages="$errors->get('company_address')" class="mt-2" />
                            </div>
                        </div>
                    </section>

                    <section class="border-t border-gray-100 pt-8">
                        <div class="mb-5">
                            <h3 class="text-lg font-semibold text-gray-900">Facturacion</h3>
                            <p class="mt-1 text-sm text-gray-500">Controla numeracion, impuestos y firma visual del PDF.</p>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-3">
                            <div class="floating-control">
                                <label for="invoice_prefix" class="floating-label">Prefijo</label>
                                <input id="invoice_prefix" name="invoice_prefix" value="{{ old('invoice_prefix', $settings['invoice_prefix']) }}" required>
                                <x-input-error :messages="$errors->get('invoice_prefix')" class="mt-2" />
                            </div>

                            <div class="floating-control">
                                <label for="invoice_next_number" class="floating-label">Siguiente numero</label>
                                <input id="invoice_next_number" type="number" name="invoice_next_number" value="{{ old('invoice_next_number', $settings['invoice_next_number']) }}" min="1" required>
                                <x-input-error :messages="$errors->get('invoice_next_number')" class="mt-2" />
                            </div>

                            <div class="floating-control">
                                <label for="invoice_tax_rate" class="floating-label">Impuesto %</label>
                                <input id="invoice_tax_rate" type="number" step="0.01" name="invoice_tax_rate" value="{{ old('invoice_tax_rate', $settings['invoice_tax_rate']) }}" min="0" max="100" required>
                                <x-input-error :messages="$errors->get('invoice_tax_rate')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-5 grid gap-5 sm:grid-cols-2">
                            <div class="floating-control">
                                <label for="invoice_signature_name" class="floating-label">Nombre del firmante</label>
                                <input id="invoice_signature_name" name="invoice_signature_name" value="{{ old('invoice_signature_name', $settings['invoice_signature_name']) }}">
                                <x-input-error :messages="$errors->get('invoice_signature_name')" class="mt-2" />
                            </div>

                            <div class="floating-control">
                                <label for="invoice_signature_role" class="floating-label">Cargo del firmante</label>
                                <input id="invoice_signature_role" name="invoice_signature_role" value="{{ old('invoice_signature_role', $settings['invoice_signature_role']) }}">
                                <x-input-error :messages="$errors->get('invoice_signature_role')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-5 rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <label for="invoice_signature" class="block text-sm font-semibold text-gray-900">Firma para PDF de facturas</label>
                            <input id="invoice_signature" type="file" name="invoice_signature" accept="image/png,image/jpeg,image/webp" class="mt-3 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-amber-900 file:px-4 file:py-2 file:text-sm file:font-bold file:text-white hover:file:bg-amber-900">
                            <p class="mt-2 text-xs text-gray-500">PNG, JPG o WEBP. Maximo 2 MB.</p>
                            <x-input-error :messages="$errors->get('invoice_signature')" class="mt-2" />

                            @if (! empty($settings['invoice_signature_path']))
                                <div class="mt-4 flex flex-wrap items-center gap-4 rounded-md bg-white p-3">
                                    <img src="{{ asset('storage/'.$settings['invoice_signature_path']) }}" alt="Firma actual" class="max-h-16 w-auto object-contain">
                                    <div>
                                        <p class="text-sm font-semibold text-amber-900">Firma cargada</p>
                                        <p class="mt-1 text-xs text-gray-500">{{ basename($settings['invoice_signature_path']) }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </section>

                    <section class="border-t border-gray-100 pt-8">
                        <div class="mb-5">
                            <h3 class="text-lg font-semibold text-gray-900">Parametros generales</h3>
                            <p class="mt-1 text-sm text-gray-500">Valores usados en reportes y documentos.</p>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="floating-control">
                                <label for="currency" class="floating-label">Moneda</label>
                                <input id="currency" name="currency" value="{{ old('currency', $settings['currency']) }}" required>
                                <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                            </div>

                            <div class="floating-control">
                                <label for="report_footer" class="floating-label">Pie de reporte</label>
                                <input id="report_footer" name="report_footer" value="{{ old('report_footer', $settings['report_footer']) }}">
                                <x-input-error :messages="$errors->get('report_footer')" class="mt-2" />
                            </div>
                        </div>
                    </section>
                </div>

                <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4">
                    <a href="{{ route('dashboard') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 font-semibold text-gray-700 hover:bg-gray-50">Cancelar</a>
                    <button class="rounded-md bg-amber-900 px-4 py-2 font-semibold text-white hover:bg-amber-900">Guardar configuracion</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
