<label class="text-sm font-medium">Nombre
    <input name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
</label>
<label class="text-sm font-medium">Empresa
    <input name="empresa" value="{{ old('empresa', $cliente->empresa) }}" class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
</label>
<label class="text-sm font-medium">Telefono
    <input name="telefono" value="{{ old('telefono', $cliente->telefono) }}" class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
</label>
