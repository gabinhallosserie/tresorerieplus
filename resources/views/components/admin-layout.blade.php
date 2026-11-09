@props(['title'])

@php
    $navigation = [
        ['route' => 'admin.dashboard', 'match' => ['admin.dashboard', 'admin.appointments.*'], 'label' => 'Rendez-vous', 'icon' => 'calendar'],
        ['route' => 'admin.availability.edit', 'match' => ['admin.availability.*'], 'label' => 'Disponibilités', 'icon' => 'clock'],
        ['route' => 'admin.messages.index', 'match' => ['admin.messages.*'], 'label' => 'Messages', 'icon' => 'mail'],
    ];
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title }} · Admin Trésorerie Plus</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @fonts
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen">
    <header class="border-b border-line bg-white">
        <div class="container-page flex flex-wrap items-center justify-between gap-3 py-3">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <x-logo />
                <span class="rounded-md bg-paper px-2 py-0.5 text-xs font-bold text-ink-soft uppercase">Admin</span>
            </a>
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('home') }}" class="px-3 py-2 font-semibold text-ink-soft hover:text-ink" target="_blank">Voir le site</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary px-4 py-2">Déconnexion</button>
                </form>
            </div>
        </div>
        <nav class="container-page flex gap-1 overflow-x-auto" aria-label="Navigation admin">
            @foreach ($navigation as $item)
                @php($isActive = request()->routeIs(...$item['match']))
                <a href="{{ route($item['route']) }}"
                   @class(['flex shrink-0 items-center gap-2 border-b-2 px-4 py-3 text-sm font-semibold', 'border-brand-600 text-brand-700' => $isActive, 'border-transparent text-ink-soft hover:text-ink' => ! $isActive])
                   @if ($isActive) aria-current="page" @endif>
                    <x-icon :name="$item['icon']" class="size-4" />
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </header>

    <main class="container-page py-8">
        <h1 class="mb-6 text-2xl font-extrabold">{{ $title }}</h1>

        @if (session('status'))
            <x-alert class="mb-6">{{ session('status') }}</x-alert>
        @endif
        @if ($errors->any())
            <x-alert type="error" class="mb-6">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
