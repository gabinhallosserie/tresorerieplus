@props([
    'title' => null,
    'description' => 'Trésorerie Plus accompagne les dirigeants de TPE, artisans, commerçants et professions libérales pour anticiper leurs variations de trésorerie au lieu de les subir. Premier rendez-vous gratuit.',
])

@php
    $navigation = [
        ['route' => 'home', 'label' => 'Accueil'],
        ['route' => 'method', 'label' => 'Notre méthode'],
        ['route' => 'network', 'label' => 'Le réseau'],
        ['route' => 'contact', 'label' => 'Contact'],
    ];
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' · Trésorerie Plus' : 'Trésorerie Plus · Conseil en prévision de trésorerie pour TPE' }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:title" content="{{ $title ?? 'Trésorerie Plus' }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="theme-color" content="#0b2545">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="flex min-h-screen flex-col">
    <a href="#contenu" class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2">Aller au contenu</a>

    <header class="sticky top-0 z-40 border-b border-line/70 bg-white/90 backdrop-blur" data-menu>
        <div class="container-page flex h-16 items-center justify-between gap-4 lg:h-20">
            <a href="{{ route('home') }}" class="shrink-0" aria-label="Trésorerie Plus, accueil">
                <x-logo />
            </a>

            <nav class="hidden items-center gap-1 lg:flex" aria-label="Navigation principale">
                @foreach ($navigation as $item)
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'rounded-full px-4 py-2 text-sm font-semibold transition',
                           'bg-brand-50 text-brand-700' => request()->routeIs($item['route']),
                           'text-ink-soft hover:text-ink' => ! request()->routeIs($item['route']),
                       ])
                       @if (request()->routeIs($item['route'])) aria-current="page" @endif>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <a href="tel:{{ config('site.advisor.phone_link') }}" class="hidden items-center gap-2 px-3 text-sm font-semibold text-ink-soft hover:text-ink xl:inline-flex">
                    <x-icon name="phone" class="size-4" />
                    {{ config('site.advisor.phone') }}
                </a>
                <a href="{{ route('booking.index') }}" class="btn btn-primary hidden sm:inline-flex">Prendre rendez-vous</a>
                <button type="button" class="inline-flex size-11 items-center justify-center rounded-full text-ink lg:hidden" aria-expanded="false" aria-controls="menu-mobile" data-menu-toggle>
                    <span class="sr-only">Ouvrir le menu</span>
                    <x-icon name="menu" class="size-6" />
                </button>
            </div>
        </div>

        <nav id="menu-mobile" class="hidden border-t border-line bg-white lg:hidden" aria-label="Navigation mobile" data-menu-panel>
            <div class="container-page flex flex-col gap-1 py-4">
                @foreach ($navigation as $item)
                    <a href="{{ route($item['route']) }}" class="rounded-xl px-4 py-3 font-semibold text-ink hover:bg-paper">{{ $item['label'] }}</a>
                @endforeach
                <a href="{{ route('booking.index') }}" class="btn btn-primary mt-2">Prendre rendez-vous</a>
                <a href="tel:{{ config('site.advisor.phone_link') }}" class="btn btn-secondary">
                    <x-icon name="phone" class="size-4" />
                    {{ config('site.advisor.phone') }}
                </a>
            </div>
        </nav>
    </header>

    <main id="contenu" class="flex-1">
        {{ $slot }}
    </main>

    <footer class="mt-24 bg-ink text-white/80">
        <div class="container-page grid gap-10 py-14 md:grid-cols-[1.4fr_1fr_1fr]">
            <div>
                <x-logo inverted />
                <p class="mt-4 max-w-sm text-sm leading-relaxed">
                    Premier réseau national de conseillers spécialisés dans l'accompagnement en prévision de trésorerie des TPE.
                </p>
            </div>

            <div>
                <p class="text-sm font-bold text-white">Votre conseillère</p>
                <address class="mt-3 space-y-1 text-sm not-italic">
                    <p>{{ config('site.advisor.name') }}</p>
                    <p>{{ config('site.advisor.street') }}<br>{{ config('site.advisor.city') }}</p>
                    <p><a class="hover:text-white" href="tel:{{ config('site.advisor.phone_link') }}">{{ config('site.advisor.phone') }}</a></p>
                    <p><a class="hover:text-white" href="mailto:{{ config('site.advisor.email') }}">{{ config('site.advisor.email') }}</a></p>
                </address>
            </div>

            <div>
                <p class="text-sm font-bold text-white">Liens utiles</p>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a class="hover:text-white" href="{{ route('booking.index') }}">Prendre rendez-vous</a></li>
                    <li><a class="hover:text-white" href="{{ config('site.forecast_tool_url') }}" rel="noopener">Tester gratuitement ma trésorerie</a></li>
                    <li><a class="hover:text-white" href="{{ route('network') }}">Rejoindre le réseau</a></li>
                    <li><a class="hover:text-white" href="{{ route('legal') }}">Mentions légales</a></li>
                    <li><a class="hover:text-white" href="{{ route('privacy') }}">Politique de confidentialité</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="container-page py-5 text-xs text-white/50">
                © {{ date('Y') }} Trésorerie Plus · {{ config('site.legal.company') }}
            </div>
        </div>
    </footer>
</body>
</html>
