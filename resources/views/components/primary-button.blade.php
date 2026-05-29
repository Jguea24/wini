<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-md border border-transparent bg-amber-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-stone-950 focus:bg-stone-950 focus:outline-none focus:ring-2 focus:ring-amber-800 focus:ring-offset-2 active:bg-stone-950']) }}>
    {{ $slot }}
</button>
