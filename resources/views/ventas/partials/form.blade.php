@php
    $cliente = $venta?->cliente;
@endphp

<section class="grid gap-5">
    <div>
        <h2 class="text-lg font-semibold text-stone-950">Datos de la venta</h2>
        <p class="mt-1 text-sm text-stone-500">Registra la fecha, forma de pago y el valor vendido.</p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div class="floating-control">
            <label for="fecha" class="floating-label">Fecha</label>
            <input id="fecha" type="date" name="fecha" value="{{ old('fecha', optional($venta?->fecha)->toDateString() ?? now()->toDateString()) }}" required>
            <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
        </div>

        <div class="floating-control">
            <label for="metodo_pago" class="floating-label">Metodo de pago</label>
            <select id="metodo_pago" name="metodo_pago" required>
                @foreach (\App\Models\Venta::METODOS_PAGO as $metodo)
                    <option value="{{ $metodo }}" @selected(old('metodo_pago', $venta?->metodo_pago) === $metodo)>{{ ucfirst($metodo) }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('metodo_pago')" class="mt-2" />
        </div>
    </div>
</section>

<section class="border-t border-stone-100 pt-6">
    <div>
        <h2 class="text-lg font-semibold text-stone-950">Datos del cliente</h2>
        <p class="mt-1 text-sm text-stone-500">Estos datos se reutilizan para facturas y registros.</p>
    </div>

    <div class="mt-5 grid gap-5 sm:grid-cols-3">
        <div class="floating-control">
            <label for="cliente_nombre" class="floating-label">Cliente</label>
            <input id="cliente_nombre" name="cliente_nombre" value="{{ old('cliente_nombre', $cliente?->nombre) }}" required>
            <x-input-error :messages="$errors->get('cliente_nombre')" class="mt-2" />
        </div>

        <div class="floating-control">
            <label for="cliente_empresa" class="floating-label">Empresa</label>
            <input id="cliente_empresa" name="cliente_empresa" value="{{ old('cliente_empresa', $cliente?->empresa) }}">
            <x-input-error :messages="$errors->get('cliente_empresa')" class="mt-2" />
        </div>

        <div class="floating-control">
            <label for="cliente_identificacion" class="floating-label">RUC/Cedula</label>
            <input id="cliente_identificacion" name="cliente_identificacion" value="{{ old('cliente_identificacion', $cliente?->identificacion) }}">
            <x-input-error :messages="$errors->get('cliente_identificacion')" class="mt-2" />
        </div>

        <div class="floating-control">
            <label for="cliente_telefono" class="floating-label">Telefono</label>
            <input id="cliente_telefono" name="cliente_telefono" value="{{ old('cliente_telefono', $cliente?->telefono) }}">
            <x-input-error :messages="$errors->get('cliente_telefono')" class="mt-2" />
        </div>

        <div class="floating-control">
            <label for="cliente_direccion" class="floating-label">Direccion</label>
            <input id="cliente_direccion" name="cliente_direccion" value="{{ old('cliente_direccion', $cliente?->direccion) }}">
            <x-input-error :messages="$errors->get('cliente_direccion')" class="mt-2" />
        </div>

        <div class="floating-control">
            <label for="cliente_correo" class="floating-label">Correo</label>
            <input id="cliente_correo" type="email" name="cliente_correo" value="{{ old('cliente_correo', $cliente?->correo) }}">
            <x-input-error :messages="$errors->get('cliente_correo')" class="mt-2" />
        </div>
    </div>
</section>

<section class="border-t border-stone-100 pt-6">
    <div>
        <h2 class="text-lg font-semibold text-stone-950">Detalle economico</h2>
        <p class="mt-1 text-sm text-stone-500">Ingresa libras vendidas y precio por libra.</p>
    </div>

    <div class="mt-5 grid gap-5 sm:grid-cols-2">
        <div class="floating-control">
            <label for="libras" class="floating-label">Libras vendidas</label>
            <input id="libras" type="number" step="0.01" min="0.01" name="libras" value="{{ old('libras', $venta?->libras) }}" required>
            <x-input-error :messages="$errors->get('libras')" class="mt-2" />
        </div>

        <div class="floating-control">
            <label for="precio_por_libra" class="floating-label">Precio por libra</label>
            <input id="precio_por_libra" type="number" step="0.01" min="0" name="precio_por_libra" value="{{ old('precio_por_libra', $venta?->precio_por_libra) }}" required>
            <x-input-error :messages="$errors->get('precio_por_libra')" class="mt-2" />
        </div>
    </div>
</section>
