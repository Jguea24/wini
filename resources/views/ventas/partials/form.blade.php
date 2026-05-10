@php
    $cliente = $venta?->cliente;
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <label class="text-sm font-medium">Fecha
        <input type="date" name="fecha" value="{{ old('fecha', optional($venta?->fecha)->toDateString() ?? now()->toDateString()) }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
    </label>
    <label class="text-sm font-medium">Metodo de pago
        <select name="metodo_pago" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
            @foreach (\App\Models\Venta::METODOS_PAGO as $metodo)
                <option value="{{ $metodo }}" @selected(old('metodo_pago', $venta?->metodo_pago) === $metodo)>{{ ucfirst($metodo) }}</option>
            @endforeach
        </select>
    </label>
</div>

<div class="grid gap-4 sm:grid-cols-3">
    <label class="text-sm font-medium">Cliente
        <input name="cliente_nombre" value="{{ old('cliente_nombre', $cliente?->nombre) }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
    </label>
    <label class="text-sm font-medium">Empresa
        <input name="cliente_empresa" value="{{ old('cliente_empresa', $cliente?->empresa) }}" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
    </label>
    <label class="text-sm font-medium">Telefono
        <input name="cliente_telefono" value="{{ old('cliente_telefono', $cliente?->telefono) }}" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
    </label>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <label class="text-sm font-medium">Libras vendidas
        <input type="number" step="0.01" min="0.01" name="libras" value="{{ old('libras', $venta?->libras) }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
    </label>
    <label class="text-sm font-medium">Precio por libra
        <input type="number" step="0.01" min="0" name="precio_por_libra" value="{{ old('precio_por_libra', $venta?->precio_por_libra) }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
    </label>
</div>
