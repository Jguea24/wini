@php($currentLocale = app()->getLocale())

<div {{ $attributes->merge(['class' => 'inline-flex rounded-md border border-stone-200 bg-white p-1 text-xs font-bold shadow-sm']) }}>
    <a href="{{ route('language.switch', 'es') }}" class="rounded px-2 py-1 {{ $currentLocale === 'es' ? 'bg-amber-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
        ES
    </a>
    <a href="{{ route('language.switch', 'en') }}" class="rounded px-2 py-1 {{ $currentLocale === 'en' ? 'bg-amber-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
        EN
    </a>
</div>
