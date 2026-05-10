<label class="text-sm font-medium">Nombre
    <input name="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
</label>
<label class="text-sm font-medium">Correo
    <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
</label>
<label class="text-sm font-medium">Rol
    <select name="role" required class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
        <option value="usuario" @selected(old('role', $user->role ?: 'usuario') === 'usuario')>Usuario</option>
    </select>
</label>
<label class="inline-flex items-center gap-2 text-sm font-medium">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->exists ? $user->is_active : true)) class="rounded border-gray-300 text-emerald-700 focus:ring-emerald-600">
    Activo
</label>
<label class="text-sm font-medium">Contrasena {{ $requirePassword ? '' : '(opcional)' }}
    <input name="password" type="password" @if($requirePassword) required @endif class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
</label>
<label class="text-sm font-medium">Confirmar contrasena
    <input name="password_confirmation" type="password" @if($requirePassword) required @endif class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
</label>
