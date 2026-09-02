<x-layouts.app title="Factura {{ $factura->numero }} | Wini">
    @php
        $cliente = $factura->venta->cliente;
    @endphp

    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-amber-900">Detalle de factura</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-stone-950">Factura {{ $factura->numero }}</h1>
            <p class="mt-1 text-sm text-stone-500">Emitida el {{ $factura->fecha_emision->format('Y-m-d') }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <form method="POST" action="{{ route('facturas.send-email', $factura) }}" class="inline">
                @csrf
                <button type="submit" class="btn-cacao inline-flex items-center gap-2" @if(! $cliente?->correo) disabled title="El cliente no tiene correo registrado" @endif>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Enviar por correo
                </button>
            </form>
            <a href="{{ route('facturas.pdf', $factura) }}" class="btn-ghost">Descargar PDF</a>
            <a href="{{ route('facturas.index') }}" class="btn-ghost">Volver</a>
        </div>
    </div>

    <section class="grid gap-6 xl:grid-cols-[1fr_360px]">
        <div class="app-card overflow-hidden bg-white p-5">
            <div class="grid gap-5 lg:grid-cols-2">
                <div>
                    <div class="flex min-h-36 items-center justify-center rounded-md border border-stone-200 bg-stone-50 p-4">
                        <img src="{{ asset('images/wini-logo.png') }}" alt="Wini" class="h-28 w-28 object-contain">
                    </div>

                    <div class="mt-4 rounded-md border border-stone-900 p-4 text-sm">
                        <h2 class="font-bold uppercase text-stone-950">{{ $company['name'] }}</h2>
                        <p class="mt-2 text-stone-700">{{ $company['address'] ?: 'Direccion matriz no registrada' }}</p>
                        <dl class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div>
                                <dt class="font-semibold text-stone-950">Direccion Matriz</dt>
                                <dd class="text-stone-600">{{ $company['address'] ?: 'No registrada' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-stone-950">Direccion Sucursal</dt>
                                <dd class="text-stone-600">{{ $company['branch_address'] ?: 'No registrada' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-stone-950">Telefono</dt>
                                <dd class="text-stone-600">{{ $company['phone'] ?: 'No registrado' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-stone-950">Correo</dt>
                                <dd class="text-stone-600">{{ $company['email'] ?: 'No registrado' }}</dd>
                            </div>
                        </dl>
                        <p class="mt-4 font-semibold text-stone-950">OBLIGADO A LLEVAR CONTABILIDAD: {{ $company['accounting_required'] }}</p>
                    </div>
                </div>

                <div class="rounded-md border border-stone-900 p-4 text-sm">
                    <p><span class="font-semibold text-stone-950">R.U.C.:</span> {{ $company['ruc'] ?: 'No registrado' }}</p>
                    <h2 class="mt-3 text-lg font-bold uppercase text-stone-950">{{ $invoiceMeta['document_type'] }}</h2>
                    <p class="mt-3"><span class="font-semibold text-stone-950">No.</span> {{ $factura->numero }}</p>
                    <p class="mt-4 font-semibold text-stone-950">NUMERO DE AUTORIZACION</p>
                    <p class="mt-1 break-all text-xs text-stone-600">{{ $invoiceMeta['authorization_number'] }}</p>

                    <dl class="mt-5 grid gap-3 sm:grid-cols-2">
                        <dt class="font-semibold text-stone-950">FECHA Y HORA DE AUTORIZACION</dt>
                        <dd class="text-stone-600">{{ $invoiceMeta['issued_at'] }}</dd>
                        <dt class="font-semibold text-stone-950">AMBIENTE</dt>
                        <dd class="text-stone-600">{{ $invoiceMeta['environment'] }}</dd>
                        <dt class="font-semibold text-stone-950">EMISION</dt>
                        <dd class="text-stone-600">{{ $invoiceMeta['emission'] }}</dd>
                    </dl>

                    <p class="mt-5 font-semibold text-stone-950">CLAVE DE ACCESO</p>
                    <div class="mt-2 break-all rounded bg-stone-950 px-3 py-2 text-center font-mono text-xs tracking-widest text-white">
                        {{ $invoiceMeta['access_key'] }}
                    </div>
                </div>
            </div>

            <div class="mt-5 rounded-md border border-stone-900 p-4 text-sm">
                <div class="grid gap-3 md:grid-cols-2">
                    <p><span class="font-semibold text-stone-950">Razon Social / Nombres y Apellidos:</span> {{ strtoupper($cliente->nombre_comercial) }}</p>
                    <p><span class="font-semibold text-stone-950">Identificacion:</span> {{ $cliente->identificacion ?: 'No registrada' }}</p>
                    <p><span class="font-semibold text-stone-950">Fecha:</span> {{ $factura->fecha_emision->format('d/m/Y') }}</p>
                    <p><span class="font-semibold text-stone-950">Guia:</span> {{ $factura->numero }}</p>
                    <p class="md:col-span-2"><span class="font-semibold text-stone-950">Direccion:</span> {{ $cliente->direccion ?: 'No registrada' }}</p>
                </div>
            </div>

            <div class="mt-5 overflow-x-auto">
                <table class="w-full min-w-[960px] border-collapse text-xs">
                    <thead>
                        <tr class="bg-stone-50">
                            <th class="border border-stone-900 px-3 py-2">Cod. Principal</th>
                            <th class="border border-stone-900 px-3 py-2">Cod. Auxiliar</th>
                            <th class="border border-stone-900 px-3 py-2 text-right">Cantidad</th>
                            <th class="border border-stone-900 px-3 py-2">Descripcion</th>
                            <th class="border border-stone-900 px-3 py-2">Detalle Adicional</th>
                            <th class="border border-stone-900 px-3 py-2 text-right">Precio Unitario</th>
                            <th class="border border-stone-900 px-3 py-2 text-right">Subsidio</th>
                            <th class="border border-stone-900 px-3 py-2 text-right">Precio sin Subsidio</th>
                            <th class="border border-stone-900 px-3 py-2 text-right">Descuento</th>
                            <th class="border border-stone-900 px-3 py-2 text-right">Precio Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-stone-900 px-3 py-3 text-center">CACAO</td>
                            <td class="border border-stone-900 px-3 py-3 text-center">001</td>
                            <td class="border border-stone-900 px-3 py-3 text-right">{{ number_format($factura->venta->libras, 2) }}</td>
                            <td class="border border-stone-900 px-3 py-3 font-semibold">VENTA DE CACAO</td>
                            <td class="border border-stone-900 px-3 py-3">{{ ucfirst($factura->venta->metodo_pago) }}</td>
                            <td class="border border-stone-900 px-3 py-3 text-right">{{ number_format($factura->venta->precio_por_libra, 2) }}</td>
                            <td class="border border-stone-900 px-3 py-3 text-right">0.00</td>
                            <td class="border border-stone-900 px-3 py-3 text-right">0.00</td>
                            <td class="border border-stone-900 px-3 py-3 text-right">{{ number_format($factura->descuento, 2) }}</td>
                            <td class="border border-stone-900 px-3 py-3 text-right font-bold">{{ number_format($factura->subtotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-5 grid gap-5 lg:grid-cols-[1fr_340px]">
                <div>
                    <div class="rounded-md border border-stone-900 p-4 text-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-semibold text-stone-950">Informacion Adicional</p>
                                <p class="mt-2"><span class="font-semibold">Email:</span> {{ $cliente->correo ?: 'No registrado' }}</p>
                                <p class="mt-2 break-all text-xs text-stone-600"><span class="font-semibold text-stone-950">Clave:</span> {{ $invoiceMeta['access_key'] }}</p>
                            </div>
                            @isset($qrCodeDataUri)
                                <img src="{{ $qrCodeDataUri }}" alt="Codigo QR" class="h-24 w-24 shrink-0 rounded border border-stone-200 bg-white p-1">
                            @endisset
                        </div>
                    </div>

                    <table class="mt-4 w-full border-collapse text-sm">
                        <tr class="bg-stone-50">
                            <th class="border border-stone-900 px-3 py-2 text-left">Forma de pago</th>
                            <th class="border border-stone-900 px-3 py-2 text-right">Valor</th>
                        </tr>
                        <tr>
                            <td class="border border-stone-900 px-3 py-2">{{ $invoiceMeta['payment_form'] }}</td>
                            <td class="border border-stone-900 px-3 py-2 text-right">{{ number_format($factura->total, 2) }}</td>
                        </tr>
                    </table>
                </div>

                <table class="w-full border-collapse text-sm">
                    <tr><td class="border border-stone-900 px-3 py-2">SUBTOTAL 15%</td><td class="border border-stone-900 px-3 py-2 text-right">{{ number_format($factura->impuesto > 0 ? max(0, $factura->subtotal - $factura->impuesto) : 0, 2) }}</td></tr>
                    <tr><td class="border border-stone-900 px-3 py-2">SUBTOTAL NO OBJETO DE IVA</td><td class="border border-stone-900 px-3 py-2 text-right">0.00</td></tr>
                    <tr><td class="border border-stone-900 px-3 py-2">SUBTOTAL EXENTO DE IVA</td><td class="border border-stone-900 px-3 py-2 text-right">0.00</td></tr>
                    <tr><td class="border border-stone-900 px-3 py-2">SUBTOTAL SIN IMPUESTOS</td><td class="border border-stone-900 px-3 py-2 text-right">{{ number_format($factura->subtotal, 2) }}</td></tr>
                    <tr><td class="border border-stone-900 px-3 py-2">TOTAL DESCUENTO</td><td class="border border-stone-900 px-3 py-2 text-right">{{ number_format($factura->descuento, 2) }}</td></tr>
                    <tr><td class="border border-stone-900 px-3 py-2">ICE</td><td class="border border-stone-900 px-3 py-2 text-right">0.00</td></tr>
                    <tr><td class="border border-stone-900 px-3 py-2">IVA 15%</td><td class="border border-stone-900 px-3 py-2 text-right">{{ number_format($factura->impuesto, 2) }}</td></tr>
                    <tr><td class="border border-stone-900 px-3 py-2">IRBPNR</td><td class="border border-stone-900 px-3 py-2 text-right">0.00</td></tr>
                    <tr><td class="border border-stone-900 px-3 py-2">PROPINA</td><td class="border border-stone-900 px-3 py-2 text-right">0.00</td></tr>
                    <tr class="font-bold"><td class="border border-stone-900 px-3 py-2">VALOR TOTAL</td><td class="border border-stone-900 px-3 py-2 text-right">{{ number_format($factura->total, 2) }}</td></tr>
                    <tr><td class="border border-stone-900 px-3 py-2">VALOR TOTAL SIN SUBSIDIO</td><td class="border border-stone-900 px-3 py-2 text-right">{{ number_format($factura->total, 2) }}</td></tr>
                    <tr><td class="border border-stone-900 px-3 py-2">AHORRO POR SUBSIDIO</td><td class="border border-stone-900 px-3 py-2 text-right">0.00</td></tr>
                </table>
            </div>
        </div>

        <aside class="app-card overflow-hidden">
            <div class="border-b border-stone-100 p-6">
                <h2 class="text-lg font-semibold text-stone-950">Control de factura</h2>
                <p class="mt-1 text-sm text-stone-500">Estado, observacion y trazabilidad del comprobante.</p>
            </div>

            <div class="space-y-2 bg-stone-50 p-6 text-sm text-stone-700">
                <p><span class="font-semibold text-stone-900">Estado:</span> {{ ucfirst($factura->estado) }}</p>
                <p><span class="font-semibold text-stone-900">Creada por:</span> {{ $factura->user?->name ?? 'Sin usuario' }}</p>
                <p><span class="font-semibold text-stone-900">Actualizada por:</span> {{ $factura->actualizador?->name ?? 'Sin cambios' }}</p>
                <p><span class="font-semibold text-stone-900">Anulada por:</span> {{ $factura->anulador?->name ?? 'No anulada' }}</p>
                <p><span class="font-semibold text-stone-900">Fecha de anulacion:</span> {{ $factura->anulada_at?->format('Y-m-d H:i') ?? 'No anulada' }}</p>
            </div>

            <form method="POST" action="{{ route('facturas.update', $factura) }}" class="grid gap-5 p-6">
                @csrf
                @method('PUT')
                <div class="floating-control">
                    <label for="estado" class="floating-label">Estado</label>
                    <select id="estado" name="estado">
                        @foreach (\App\Models\Factura::ESTADOS as $estado)
                            <option value="{{ $estado }}" @selected($factura->estado === $estado)>{{ ucfirst($estado) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                </div>
                <div class="floating-control">
                    <label for="observacion" class="floating-label">Observacion</label>
                    <textarea id="observacion" name="observacion" rows="4">{{ old('observacion', $factura->observacion) }}</textarea>
                    <x-input-error :messages="$errors->get('observacion')" class="mt-2" />
                </div>
                <button class="btn-cacao w-full">Guardar cambios</button>
            </form>
        </aside>
    </section>
</x-layouts.app>
