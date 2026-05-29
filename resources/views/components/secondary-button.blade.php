<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-md border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-stone-700 shadow-sm transition hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-amber-800 focus:ring-offset-2 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
