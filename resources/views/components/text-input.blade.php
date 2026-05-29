@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-md border-stone-300 shadow-sm focus:border-amber-900 focus:ring-amber-900']) }}>
