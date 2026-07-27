<x-app-layout>
    @php
        $avatarColors = [
            'bg-sky-200 text-sky-950',
            'bg-rose-100 text-rose-800',
            'bg-slate-200 text-slate-800',
            'bg-emerald-100 text-emerald-800',
            'bg-orange-100 text-orange-800',
            'bg-amber-100 text-amber-900',
        ];

        $initials = function ($name) {
            return collect(explode(' ', trim($name)))
                ->filter()
                ->take(2)
                ->map(fn ($part) => mb_substr($part, 0, 1))
                ->join('');
        };
    @endphp

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Organigrama
                </h2>
                <p class="mt-1 text-sm text-gray-500">Estructura organizacional de Wini.</p>
            </div>

            @if (auth()->user()?->isAdmin())
                <a href="{{ route('admin.users.index') }}" class="rounded-md bg-amber-900 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-950">
                    Editar equipo
                </a>
            @endif
        </div>
    </x-slot>

    <div class="bg-white py-8 sm:py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (! $ceo && $directors->isEmpty() && $support->isEmpty())
                <section class="rounded-lg border border-dashed border-stone-300 bg-stone-50 px-6 py-12 text-center">
                    <img src="{{ asset('images/wini-logo.png') }}" alt="Wini" class="mx-auto h-24 w-24 object-contain">
                    <h1 class="mt-5 text-2xl font-semibold text-stone-900">Organigrama de Wini</h1>
                    <p class="mx-auto mt-2 max-w-xl text-sm text-stone-500">
                        Aun no hay usuarios visibles en el organigrama. Activa "Mostrar en organigrama" y asigna cargo, nivel y orden desde Usuarios.
                    </p>
                </section>
            @else
                <div class="overflow-x-auto pb-6">
                    <section class="relative mx-auto min-w-[1120px] max-w-[1220px] rounded-lg bg-white px-6 py-10">
                        <div class="grid grid-cols-[1fr_1.2fr_1fr] items-center gap-8">
                            <div class="text-center">
                                <img src="{{ asset('images/wini-logo.png') }}" alt="Wini" class="mx-auto h-40 w-40 object-contain">
                                <h1 class="mt-4 text-3xl font-medium text-stone-950">Organigrama de Wini</h1>
                            </div>

                            <div class="relative flex justify-center">
                                @if ($ceo && ($directors->isNotEmpty() || $support->isNotEmpty()))
                                    <div class="absolute left-1/2 top-full h-28 w-0.5 -translate-x-1/2 bg-stone-900"></div>
                                @endif

                                @if ($ceo)
                                    <article class="relative w-80 rounded-[22px] bg-violet-700 px-8 pb-5 pt-11 text-center text-white shadow-sm">
                                        <span class="absolute left-1/2 top-0 flex h-24 w-24 -translate-x-1/2 -translate-y-1/2 items-center justify-center overflow-hidden rounded-full {{ $avatarColors[0] }} text-xl font-bold ring-8 ring-white">
                                            @if ($ceo->profile_photo_path)
                                                <img src="{{ asset('storage/'.$ceo->profile_photo_path) }}" alt="{{ $ceo->name }}" class="h-full w-full object-cover">
                                            @else
                                                {{ $initials($ceo->name) }}
                                            @endif
                                        </span>
                                        <h3 class="text-2xl font-extrabold tracking-wide">{{ mb_strtoupper($ceo->name) }}</h3>
                                        <p class="mt-1 text-sm font-bold">{{ $ceo->org_chart_position ?: 'CEO' }}</p>
                                        <p class="mt-6 text-sm leading-5">{{ $ceo->email }}</p>
                                    </article>
                                @else
                                    <div class="rounded-[22px] border border-dashed border-violet-200 bg-violet-50 px-8 py-8 text-center">
                                        <p class="text-sm font-semibold text-violet-700">Sin CEO asignado</p>
                                    </div>
                                @endif
                            </div>

                            <div></div>
                        </div>

                        @if ($directors->isNotEmpty())
                            <div class="relative mt-24">
                                <div class="absolute left-[9%] right-[9%] top-[84px] h-0.5 bg-stone-900"></div>
                                <div class="grid grid-cols-5 gap-6">
                                    @foreach ($directors as $member)
                                        @php($centerColumn = $loop->iteration === (int) ceil($directors->count() / 2))
                                        <article class="relative flex min-h-40 items-end">
                                            @if ($centerColumn && $ceo)
                                                <div class="absolute left-1/2 -top-24 h-24 w-0.5 -translate-x-1/2 bg-stone-900"></div>
                                            @endif
                                            @if ($centerColumn && $support->isNotEmpty())
                                                <div class="absolute left-1/2 top-full h-28 w-0.5 -translate-x-1/2 bg-stone-900"></div>
                                            @endif

                                            <div class="relative w-full rounded-[22px] bg-violet-500 px-5 pb-8 pt-12 text-center text-white">
                                                <span class="absolute left-1/2 top-0 flex h-24 w-24 -translate-x-1/2 -translate-y-1/2 items-center justify-center overflow-hidden rounded-full {{ $avatarColors[$loop->index % count($avatarColors)] }} text-xl font-bold ring-8 ring-white">
                                                    @if ($member->profile_photo_path)
                                                        <img src="{{ asset('storage/'.$member->profile_photo_path) }}" alt="{{ $member->name }}" class="h-full w-full object-cover">
                                                    @else
                                                        {{ $initials($member->name) }}
                                                    @endif
                                                </span>
                                                <h3 class="text-[22px] font-medium uppercase leading-6 tracking-wide">{{ $member->name }}</h3>
                                                <p class="mt-1 text-base font-bold leading-5">{{ $member->org_chart_position ?: 'Sin cargo' }}</p>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($support->isNotEmpty())
                            <div class="mt-28 flex flex-wrap justify-center gap-6">
                                @foreach ($support as $member)
                                    <article class="relative w-80 rounded-[22px] bg-violet-400 px-6 pb-9 pt-12 text-center text-white">
                                        <span class="absolute left-1/2 top-0 flex h-24 w-24 -translate-x-1/2 -translate-y-1/2 items-center justify-center overflow-hidden rounded-full {{ $avatarColors[$loop->index % count($avatarColors)] }} text-xl font-bold ring-8 ring-white">
                                            @if ($member->profile_photo_path)
                                                <img src="{{ asset('storage/'.$member->profile_photo_path) }}" alt="{{ $member->name }}" class="h-full w-full object-cover">
                                            @else
                                                {{ $initials($member->name) }}
                                            @endif
                                        </span>
                                        <h3 class="text-2xl font-bold uppercase leading-7 tracking-wide">{{ $member->name }}</h3>
                                        <p class="mt-1 text-base font-medium leading-5">{{ $member->org_chart_position ?: 'Sin cargo' }}</p>
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
