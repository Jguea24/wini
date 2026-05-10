<label class="text-sm font-medium">Fecha
    <input type="date" name="fecha" value="{{ old('fecha', optional($gasto?->fecha)->toDateString() ?? now()->toDateString()) }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
</label>

<label class="text-sm font-medium">Tipo de gasto
    <select name="tipo" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
        @foreach (\App\Models\Gasto::TIPOS as $tipo)
            <option value="{{ $tipo }}" @selected(old('tipo', $gasto?->tipo) === $tipo)>{{ str_replace('_', ' ', ucfirst($tipo)) }}</option>
        @endforeach
    </select>
</label>

<label class="text-sm font-medium">Descripcion
    <textarea name="descripcion" rows="3" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">{{ old('descripcion', $gasto?->descripcion) }}</textarea>
</label>

<label class="text-sm font-medium">Monto
    <input type="number" step="0.01" min="0" name="monto" value="{{ old('monto', $gasto?->monto) }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
</label>
