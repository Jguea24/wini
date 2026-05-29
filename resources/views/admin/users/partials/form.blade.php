<div class="grid gap-5 md:grid-cols-2">
    <div class="floating-control">
        <label for="name" class="floating-label">Nombre</label>
        <input id="name" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="floating-control">
        <label for="email" class="floating-label">Correo</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="floating-control">
        <label for="role" class="floating-label">Rol</label>
        <select id="role" name="role" required>
            <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
            <option value="usuario" @selected(old('role', $user->role ?: 'usuario') === 'usuario')>Usuario</option>
        </select>
        @error('role')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
        <label class="inline-flex items-center gap-3 text-sm font-medium text-gray-800">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->exists ? $user->is_active : true)) class="rounded border-gray-300 text-amber-900 focus:ring-amber-800">
            Usuario activo
        </label>
    </div>

    <div x-data="{ showPassword: false }">
        <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Contrasena {{ $requirePassword ? '' : '(opcional)' }}</label>
        <div class="relative">
            <input
                id="password"
                name="password"
                x-bind:type="showPassword ? 'text' : 'password'"
                @if($requirePassword) required @endif
                autocomplete="new-password"
                style="display:block;width:100%;height:44px;border:1px solid #44403c;border-radius:8px;background:#fff;padding:8px 44px 8px 12px;color:#1c1917;"
            >
            <button
                type="button"
                x-on:click="showPassword = ! showPassword"
                class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-stone-500 hover:text-stone-700"
                aria-label="Mostrar u ocultar contrasena"
            >
                <svg x-show="! showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m2 2 20 20" />
                    <path d="M6.7 6.7C3.7 8.7 2 12 2 12s3.6 7 10 7c1.8 0 3.4-.5 4.7-1.2" />
                    <path d="M19.6 14.6C21.1 13.3 22 12 22 12s-3.6-7-10-7c-.9 0-1.8.1-2.6.4" />
                    <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" />
                </svg>
                <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
            </button>
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div x-data="{ showPasswordConfirmation: false }">
        <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">Confirmar contrasena</label>
        <div class="relative">
            <input
                id="password_confirmation"
                name="password_confirmation"
                x-bind:type="showPasswordConfirmation ? 'text' : 'password'"
                @if($requirePassword) required @endif
                autocomplete="new-password"
                style="display:block;width:100%;height:44px;border:1px solid #44403c;border-radius:8px;background:#fff;padding:8px 44px 8px 12px;color:#1c1917;"
            >
            <button
                type="button"
                x-on:click="showPasswordConfirmation = ! showPasswordConfirmation"
                class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-stone-500 hover:text-stone-700"
                aria-label="Mostrar u ocultar confirmacion de contrasena"
            >
                <svg x-show="! showPasswordConfirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m2 2 20 20" />
                    <path d="M6.7 6.7C3.7 8.7 2 12 2 12s3.6 7 10 7c1.8 0 3.4-.5 4.7-1.2" />
                    <path d="M19.6 14.6C21.1 13.3 22 12 22 12s-3.6-7-10-7c-.9 0-1.8.1-2.6.4" />
                    <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" />
                </svg>
                <svg x-show="showPasswordConfirmation" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
            </button>
        </div>
        @error('password_confirmation')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
