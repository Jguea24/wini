@php
    $inputClass = 'peer block w-full rounded-xl border border-white/20 bg-white py-3.5 pe-4 ps-11 text-sm font-semibold text-stone-950 shadow-sm outline-none transition-all duration-300 placeholder:text-stone-400 hover:border-[#D8B789] hover:shadow-md focus:border-[#D8B789] focus:ring-4 focus:ring-[#D8B789]/25';
    $labelClass = 'mb-2 block text-xs font-bold uppercase tracking-wide text-[#F5F1EB]';
    $iconClass = 'pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400 transition-colors duration-300 peer-focus:text-[#6F4E37]';
    $fields = [
        ['name' => 'nombre', 'label' => 'Nombre', 'placeholder' => 'Nombre del cliente', 'value' => old('nombre', $cliente->nombre), 'required' => true, 'type' => 'text', 'span' => '', 'icon' => 'user'],
        ['name' => 'empresa', 'label' => 'Empresa', 'placeholder' => 'Nombre comercial o empresa', 'value' => old('empresa', $cliente->empresa), 'required' => false, 'type' => 'text', 'span' => '', 'icon' => 'building'],
        ['name' => 'identificacion', 'label' => 'RUC/Cedula', 'placeholder' => 'Ingrese RUC o cedula', 'value' => old('identificacion', $cliente->identificacion), 'required' => false, 'type' => 'text', 'span' => '', 'icon' => 'id'],
        ['name' => 'telefono', 'label' => 'Telefono', 'placeholder' => 'Numero de contacto', 'value' => old('telefono', $cliente->telefono), 'required' => false, 'type' => 'tel', 'span' => '', 'icon' => 'phone'],
        ['name' => 'direccion', 'label' => 'Direccion', 'placeholder' => 'Direccion completa', 'value' => old('direccion', $cliente->direccion), 'required' => false, 'type' => 'text', 'span' => 'md:col-span-2', 'icon' => 'map'],
        ['name' => 'correo', 'label' => 'Correo electronico', 'placeholder' => 'cliente@empresa.com', 'value' => old('correo', $cliente->correo), 'required' => false, 'type' => 'email', 'span' => 'md:col-span-2', 'icon' => 'mail'],
    ];
@endphp

<div class="relative min-h-[calc(100vh-10rem)] overflow-hidden bg-[#FAF8F5] px-4 py-10 sm:px-6 lg:px-8">
    <div class="absolute inset-0 opacity-60 [background-image:radial-gradient(circle_at_1px_1px,rgba(111,78,55,.12)_1px,transparent_0)] [background-size:24px_24px]"></div>
    <div class="absolute left-8 top-10 h-40 w-40 rounded-full bg-[#F5F1EB] blur-3xl"></div>
    <div class="absolute bottom-10 right-10 h-56 w-56 rounded-full bg-[#6F4E37]/10 blur-3xl"></div>

    <div class="relative mx-auto flex min-h-[calc(100vh-15rem)] max-w-4xl items-center justify-center">
        <div class="w-full max-w-3xl rounded-2xl border border-[#8A6A51] bg-[#6F4E37] p-6 shadow-2xl shadow-stone-400/50 backdrop-blur transition-all duration-300 sm:p-8">
            <div class="mx-auto max-w-2xl">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#F5F1EB] text-[#6F4E37] shadow-lg shadow-black/20">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#D8B789]">WINI S.A.S.</p>
                            <h1 class="mt-1 text-2xl font-bold tracking-tight text-white">{{ $title }}</h1>
                            <p class="mt-1 text-sm leading-6 text-[#F5F1EB]/80">{{ $description }}</p>
                        </div>
                    </div>

                    <a href="{{ route('clientes.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-white shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:bg-white/15 hover:shadow-md">
                        Volver
                    </a>
                </div>

                <div class="mt-6 h-px bg-gradient-to-r from-transparent via-white/25 to-transparent"></div>

                @if ($errors->any())
                    <div class="mt-6 rounded-xl border border-red-200/60 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                        <p class="font-semibold">Revisa los datos ingresados.</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ $action }}" class="mt-6" data-ecuador-id-form>
                    @csrf
                    @isset($method)
                        @method($method)
                    @endisset

                    <div class="grid grid-cols-1 gap-x-5 gap-y-4 md:grid-cols-2">
                        @foreach ($fields as $field)
                            <div class="{{ $field['span'] }}">
                                <label for="{{ $field['name'] }}" class="{{ $labelClass }}">{{ $field['label'] }}</label>
                                <div class="relative">
                                    <input
                                        id="{{ $field['name'] }}"
                                        name="{{ $field['name'] }}"
                                        type="{{ $field['type'] }}"
                                        value="{{ $field['value'] }}"
                                        placeholder="{{ $field['placeholder'] }}"
                                        @required($field['required'])
                                        @if ($field['name'] === 'identificacion')
                                            maxlength="13"
                                            inputmode="numeric"
                                            autocomplete="off"
                                            data-ecuador-id-input
                                        @endif
                                        class="{{ $inputClass }} @error($field['name']) border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                    >

                                    @switch($field['icon'])
                                        @case('user')
                                            <svg class="{{ $iconClass }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0" /></svg>
                                            @break
                                        @case('building')
                                            <svg class="{{ $iconClass }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M5.25 21V4.5A1.5 1.5 0 0 1 6.75 3h10.5a1.5 1.5 0 0 1 1.5 1.5V21M8.25 7.5h1.5M8.25 10.5h1.5M8.25 13.5h1.5M14.25 7.5h1.5M14.25 10.5h1.5M14.25 13.5h1.5" /></svg>
                                            @break
                                        @case('id')
                                            <svg class="{{ $iconClass }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5h-15A1.5 1.5 0 0 0 3 6v12a1.5 1.5 0 0 0 1.5 1.5ZM8.25 9.75a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3ZM5.25 15.75a3 3 0 0 1 6 0" /></svg>
                                            @break
                                        @case('phone')
                                            <svg class="{{ $iconClass }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 0 0-1.173.417l-.97 1.293a1.125 1.125 0 0 1-1.21.38 12.035 12.035 0 0 1-7.143-7.143 1.125 1.125 0 0 1 .38-1.21l1.293-.97c.363-.272.527-.739.417-1.173L6.963 3.102A1.125 1.125 0 0 0 5.872 2.25H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg>
                                            @break
                                        @case('map')
                                            <svg class="{{ $iconClass }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.438A1.125 1.125 0 0 0 21 17.304V4.82a1.125 1.125 0 0 0-1.628-1.006l-3.869 1.934a1.125 1.125 0 0 1-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.69A1.125 1.125 0 0 0 3 6.696V19.18a1.125 1.125 0 0 0 1.628 1.006l3.869-1.934a1.125 1.125 0 0 1 1.006 0l4.994 2.497a1.125 1.125 0 0 0 1.006 0Z" /></svg>
                                            @break
                                        @case('mail')
                                            <svg class="{{ $iconClass }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0l-7.5-4.615a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                                            @break
                                    @endswitch
                                </div>
                                @if ($field['name'] === 'identificacion')
                                    <p class="mt-2 hidden text-sm font-medium text-red-100" data-ecuador-id-message></p>
                                @endif
                                @error($field['name'])
                                    <p class="mt-2 text-sm font-medium text-red-100">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-7 flex flex-col-reverse gap-3 border-t border-white/20 pt-5 sm:flex-row sm:justify-end">
                        <a href="{{ route('clientes.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white/10 px-5 py-3 text-sm font-bold text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-white/15">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F5F1EB] px-5 py-3 text-sm font-bold text-[#6F4E37] shadow-lg shadow-black/20 transition-all duration-300 hover:-translate-y-0.5 hover:bg-white hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[#F5F1EB]/30">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                            </svg>
                            {{ $submitLabel }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (() => {
        const form = document.querySelector('[data-ecuador-id-form]');
        const input = document.querySelector('[data-ecuador-id-input]');
        const message = document.querySelector('[data-ecuador-id-message]');

        if (!form || !input || !message) {
            return;
        }

        function isValidCedula(value) {
            if (!/^\d{10}$/.test(value)) {
                return false;
            }

            const province = Number(value.substring(0, 2));

            if (province < 1 || province > 24) {
                return false;
            }

            const digits = value.split('').map(Number);
            let sum = 0;

            for (let index = 0; index < 9; index++) {
                let current = index % 2 === 0 ? digits[index] * 2 : digits[index];
                sum += current > 9 ? current - 9 : current;
            }

            const verifier = (10 - (sum % 10)) % 10;

            return verifier === digits[9];
        }

        function isValidRuc(value, coefficients, verifierPosition) {
            const province = Number(value.substring(0, 2));

            if (province < 1 || province > 24) {
                return false;
            }

            const digits = value.split('').map(Number);
            const sum = coefficients.reduce((total, coefficient, index) => total + (digits[index] * coefficient), 0);
            let verifier = 11 - (sum % 11);

            if (verifier === 11) {
                verifier = 0;
            }

            if (verifier === 10) {
                verifier = 1;
            }

            return verifier === digits[verifierPosition];
        }

        function validateIdentification(value) {
            if (value === '') {
                return { valid: true, text: '' };
            }

            if (!/^\d+$/.test(value)) {
                return { valid: false, text: 'Solo se permiten numeros.' };
            }

            if (value.length === 10) {
                return isValidCedula(value)
                    ? { valid: true, text: 'Cedula valida.' }
                    : { valid: false, text: 'Cedula digitada incorrectamente.' };
            }

            if (value.length === 13) {
                if (value.substring(10) === '000') {
                    return { valid: false, text: 'El RUC debe terminar con un establecimiento valido.' };
                }

                const thirdDigit = Number(value[2]);

                if (thirdDigit < 6) {
                    return isValidCedula(value.substring(0, 10))
                        ? { valid: true, text: 'RUC valido.' }
                        : { valid: false, text: 'RUC digitado incorrectamente.' };
                }

                if (thirdDigit === 6) {
                    return isValidRuc(value, [3, 2, 7, 6, 5, 4, 3, 2], 8)
                        ? { valid: true, text: 'RUC valido.' }
                        : { valid: false, text: 'RUC digitado incorrectamente.' };
                }

                if (thirdDigit === 9) {
                    return isValidRuc(value, [4, 3, 2, 7, 6, 5, 4, 3, 2], 9)
                        ? { valid: true, text: 'RUC valido.' }
                        : { valid: false, text: 'RUC digitado incorrectamente.' };
                }
            }

            return { valid: false, text: 'Ingrese una cedula de 10 digitos o un RUC de 13 digitos.' };
        }

        function paintValidation() {
            const result = validateIdentification(input.value);

            message.classList.toggle('hidden', result.text === '');
            message.classList.toggle('text-red-100', !result.valid);
            message.classList.toggle('text-emerald-100', result.valid);
            message.textContent = result.text;
            input.classList.toggle('border-red-300', !result.valid);
            input.classList.toggle('focus:border-red-500', !result.valid);

            return result.valid;
        }

        input.addEventListener('input', () => {
            input.value = input.value.replace(/\D/g, '').slice(0, 13);
            paintValidation();
        });

        input.addEventListener('blur', paintValidation);

        form.addEventListener('submit', (event) => {
            if (!paintValidation()) {
                event.preventDefault();
                input.focus();
            }
        });
    })();
</script>
