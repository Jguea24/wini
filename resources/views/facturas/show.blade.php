<x-layouts.app title="Factura {{ $factura->numero }} | Wini">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Factura {{ $factura->numero }}</h1>
            <p class="mt-1 text-stone-600">Emitida el {{ $factura->fecha_emision->format('Y-m-d') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('facturas.pdf', $factura) }}" class="rounded-md bg-stone-900 px-4 py-2 font-semibold text-white hover:bg-stone-700">PDF</a>
            <a href="{{ route('facturas.index') }}" class="rounded-md border border-stone-300 bg-white px-4 py-2 font-semibold hover:bg-stone-100">Volver</a>
        </div>
    </div>

    <section class="mt-6 grid gap-6 lg:grid-cols-[1fr_360px]">
        <div class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4 border-b border-stone-200 pb-5">
                <div>
                    <p class="text-sm text-stone-500">Cliente</p>
                    <h2 class="mt-1 text-xl font-bold">{{ $factura->venta->cliente->nombre_comercial }}</h2>
                    <div class="mt-2 space-y-1 text-sm text-stone-600">
                        <p>RUC/Cédula: {{ $factura->venta->cliente->identificacion ?: 'No registrado' }}</p>
                        <p>Dirección: {{ $factura->venta->cliente->direccion ?: 'No registrada' }}</p>
                        <p>Teléfono: {{ $factura->venta->cliente->telefono ?: 'No registrado' }}</p>
                        <p>Correo: {{ $factura->venta->cliente->correo ?: 'No registrado' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-stone-500">Estado</p>
                    <p class="mt-1 font-bold">{{ ucfirst($factura->estado) }}</p>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full min-w-[620px] text-left text-sm">
                    <thead class="text-stone-500">
                        <tr><th class="py-2">Detalle</th><th class="py-2 text-right">Libras</th><th class="py-2 text-right">Precio/lb</th><th class="py-2 text-right">Total</th></tr>
                    </thead>
                    <tbody class="border-t border-stone-200">
                        <tr>
                            <td class="py-4">Venta de cacao</td>
                            <td class="py-4 text-right">{{ number_format($factura->venta->libras, 2) }}</td>
                            <td class="py-4 text-right">${{ number_format($factura->venta->precio_por_libra, 2) }}</td>
                            <td class="py-4 text-right font-semibold">${{ number_format($factura->subtotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <div class="w-full max-w-sm space-y-2 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>${{ number_format($factura->subtotal, 2) }}</span></div>
                    <div class="flex justify-between"><span>Descuento</span><span>${{ number_format($factura->descuento, 2) }}</span></div>
                    <div class="flex justify-between"><span>Impuesto</span><span>${{ number_format($factura->impuesto, 2) }}</span></div>
                    <div class="flex justify-between border-t border-stone-200 pt-2 text-lg font-bold"><span>Total</span><span>${{ number_format($factura->total, 2) }}</span></div>
                </div>
            </div>
        </div>

        <aside class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Control de factura</h2>
            <div class="mt-4 space-y-2 rounded-md bg-stone-50 p-4 text-sm text-stone-700">
                <p><span class="font-semibold">Creada por:</span> {{ $factura->user?->name ?? 'Sin usuario' }}</p>
                <p><span class="font-semibold">Actualizada por:</span> {{ $factura->actualizador?->name ?? 'Sin cambios' }}</p>
                <p><span class="font-semibold">Anulada por:</span> {{ $factura->anulador?->name ?? 'No anulada' }}</p>
                <p><span class="font-semibold">Fecha de anulación:</span> {{ $factura->anulada_at?->format('Y-m-d H:i') ?? 'No anulada' }}</p>
            </div>
            <form method="POST" action="{{ route('facturas.update', $factura) }}" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                <label class="block text-sm font-medium">Estado
                    <select name="estado" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
                        @foreach (\App\Models\Factura::ESTADOS as $estado)
                            <option value="{{ $estado }}" @selected($factura->estado === $estado)>{{ ucfirst($estado) }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block text-sm font-medium">Observación
                    <textarea name="observacion" rows="4" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">{{ old('observacion', $factura->observacion) }}</textarea>
                </label>
                <button class="w-full rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800">Guardar cambios</button>
            </form>
        </aside>
    </section>
</x-layouts.app>
