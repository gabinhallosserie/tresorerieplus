@props(['inverted' => false])

<span {{ $attributes->class(['inline-flex items-center', 'rounded-lg bg-white px-3 py-2' => $inverted]) }}>
    <img src="{{ asset('images/logo-tresorerie-plus.png') }}" alt="Trésorerie Plus" width="299" height="40" class="h-7 w-auto sm:h-8">
</span>
