<x-layouts.app title="Factura {{ $factura->numero }} | Wini">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-amber-900">Detalle de factura</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-stone-950">Factura {{ $factura->numero }}</h1>
            <p class="mt-1 text-sm text-stone-500">Emitida el {{ $factura->fecha_emision->format('Y-m-d') }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('facturas.pdf', $factura) }}" class="btn-cacao">Descargar PDF</a>
            <a href="{{ route('facturas.index') }}" class="btn-ghost">Volver</a>
        </div>
    </div>

    <section class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <div class="app-card overflow-hidden">
            <div class="grid gap-5 border-b border-stone-100 p-6 md:grid-cols-[1fr_auto]">
                <div>
                    <p class="text-sm font-medium text-stone-500">Cliente</p>
                    <h2 class="mt-1 text-2xl font-bold text-stone-950">{{ $factura->venta->cliente->nombre_comercial }}</h2>
                    <div class="mt-4 grid gap-3 text-sm text-stone-600 sm:grid-cols-2">
                        <p><span class="font-semibold text-stone-900">RUC/Cedula:</span> {{ $factura->venta->cliente->identificacion ?: 'No registrado' }}</p>
                        <p><span class="font-semibold text-stone-900">Telefono:</span> {{ $factura->venta->cliente->telefono ?: 'No registrado' }}</p>
                        <p><span class="font-semibold text-stone-900">Direccion:</span> {{ $factura->venta->cliente->direccion ?: 'No registrada' }}</p>
                        <p><span class="font-semibold text-stone-900">Correo:</span> {{ $factura->venta->cliente->correo ?: 'No registrado' }}</p>
                    </div>
                </div>
                <div class="rounded-lg bg-amber-50 px-5 py-4 text-right">
                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-900">Estado</p>
                    <p class="mt-1 text-xl font-bold text-stone-950">{{ ucfirst($factura->estado) }}</p>
                </div>
            </div>

            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[620px] text-left text-sm">
                        <thead class="bg-stone-50 text-xs font-semibold uppercase tracking-wide text-stone-500">
                            <tr>
                                <th class="px-4 py-3">Detalle</th>
                                <th class="px-4 py-3 text-right">Libras</th>
                                <th class="px-4 py-3 text-right">Precio/lb</th>
                                <th class="px-4 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            <tr>
                                <td class="px-4 py-4 font-semibold text-stone-950">Venta de cacao</td>
                                <td class="px-4 py-4 text-right text-stone-700">{{ number_format($factura->venta->libras, 2) }}</td>
                                <td class="px-4 py-4 text-right text-stone-700">${{ number_format($factura->venta->precio_por_libra, 2) }}</td>
                                <td class="px-4 py-4 text-right font-bold text-amber-900">${{ number_format($factura->subtotal, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end">
                    <div class="w-full max-w-sm rounded-lg border border-stone-200 bg-stone-50 p-4 text-sm">
                        <div class="flex justify-between py-1"><span class="text-stone-600">Subtotal</span><span class="font-semibold">${{ number_format($factura->subtotal, 2) }}</span></div>
                        <div class="flex justify-between py-1"><span class="text-stone-600">Descuento</span><span class="font-semibold">${{ number_format($factura->descuento, 2) }}</span></div>
                        <div class="flex justify-between py-1"><span class="text-stone-600">Impuesto</span><span class="font-semibold">${{ number_format($factura->impuesto, 2) }}</span></div>
                        <div class="mt-2 flex justify-between border-t border-stone-200 pt-3 text-lg font-bold text-stone-950">
                            <span>Total</span>
                            <span class="text-amber-900">${{ number_format($factura->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <aside class="app-card overflow-hidden">
            <div class="border-b border-stone-100 p-6">
                <h2 class="text-lg font-semibold text-stone-950">Control de factura</h2>
                <p class="mt-1 text-sm text-stone-500">Estado, observacion y trazabilidad del comprobante.</p>
            </div>

            <div class="space-y-2 bg-stone-50 p-6 text-sm text-stone-700">
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
