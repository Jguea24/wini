<section class="grid gap-5">
    <div>
        <h2 class="text-lg font-semibold text-stone-950">Datos de la inversion</h2>
        <p class="mt-1 text-sm text-stone-500">Define el destino del capital para reportar mejor el flujo financiero.</p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div class="floating-control">
            <label for="fecha" class="floating-label">Fecha</label>
            <input id="fecha" type="date" name="fecha" value="{{ old('fecha', optional($inversion?->fecha)->toDateString() ?? now()->toDateString()) }}" required>
            <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
        </div>

        <div class="floating-control">
            <label for="tipo" class="floating-label">Tipo de inversion</label>
            <select id="tipo" name="tipo" required>
                @foreach (\App\Models\Inversion::TIPOS as $tipo)
                    <option value="{{ $tipo }}" @selected(old('tipo', $inversion?->tipo) === $tipo)>{{ str_replace('_', ' ', ucfirst($tipo)) }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
        </div>

        <div class="floating-control sm:col-span-2">
            <label for="concepto" class="floating-label">Concepto</label>
            <input id="concepto" type="text" name="concepto" value="{{ old('concepto', $inversion?->concepto) }}" required maxlength="160" placeholder="Ej. Fermentadora nueva">
            <x-input-error :messages="$errors->get('concepto')" class="mt-2" />
        </div>

        <div class="floating-control sm:col-span-2">
            <label for="descripcion" class="floating-label">Descripcion</label>
            <textarea id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $inversion?->descripcion) }}</textarea>
            <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
        </div>

        <div class="floating-control">
            <label for="monto" class="floating-label">Monto invertido</label>
            <input id="monto" type="number" step="0.01" min="0" name="monto" value="{{ old('monto', $inversion?->monto) }}" required>
            <x-input-error :messages="$errors->get('monto')" class="mt-2" />
        </div>
    </div>
</section>
