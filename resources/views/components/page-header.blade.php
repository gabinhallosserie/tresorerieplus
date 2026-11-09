@props(['eyebrow' => null, 'title'])

<section class="border-b border-line bg-white">
    <div class="container-page py-14 sm:py-20">
        @if ($eyebrow)
            <p class="eyebrow">{{ $eyebrow }}</p>
        @endif
        <h1 class="mt-3 max-w-3xl text-4xl font-extrabold tracking-tight text-balance sm:text-5xl">{{ $title }}</h1>
        @if ($slot->isNotEmpty())
            <div class="mt-5 max-w-2xl text-lg leading-relaxed text-ink-soft">{{ $slot }}</div>
        @endif
    </div>
</section>
