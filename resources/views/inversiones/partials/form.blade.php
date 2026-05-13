<label class="text-sm font-medium text-stone-700">Fecha
    <input type="date" name="fecha" value="{{ old('fecha', optional($inversion?->fecha)->toDateString() ?? now()->toDateString()) }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600">
</label>

<label class="text-sm font-medium text-stone-700">Tipo de inversion
    <select name="tipo" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600">
        @foreach (\App\Models\Inversion::TIPOS as $tipo)
            <option value="{{ $tipo }}" @selected(old('tipo', $inversion?->tipo) === $tipo)>{{ str_replace('_', ' ', ucfirst($tipo)) }}</option>
        @endforeach
    </select>
</label>

<label class="text-sm font-medium text-stone-700">Concepto
    <input type="text" name="concepto" value="{{ old('concepto', $inversion?->concepto) }}" required maxlength="160" placeholder="Ej. Compra de fermentadora" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600">
</label>

<label class="text-sm font-medium text-stone-700">Descripcion
    <textarea name="descripcion" rows="3" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600">{{ old('descripcion', $inversion?->descripcion) }}</textarea>
</label>

<label class="text-sm font-medium text-stone-700">Monto invertido
    <input type="number" step="0.01" min="0" name="monto" value="{{ old('monto', $inversion?->monto) }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600">
</label>
