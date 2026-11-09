@props(['name'])

<svg {{ $attributes->merge(['aria-hidden' => 'true']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    @switch($name)
        @case('phone')
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z" />
            @break
        @case('menu')
            <path d="M4 7h16M4 12h16M4 17h16" />
            @break
        @case('calendar')
            <rect x="3" y="4.5" width="18" height="16.5" rx="2.5" /><path d="M16 2.5v4M8 2.5v4M3 10h18" />
            @break
        @case('mail')
            <rect x="2.5" y="4.5" width="19" height="15" rx="2.5" /><path d="m3 6.5 9 6.5 9-6.5" />
            @break
        @case('pin')
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" /><circle cx="12" cy="10" r="3" />
            @break
        @case('check')
            <path d="M20 6 9 17l-5-5" />
            @break
        @case('arrow')
            <path d="M5 12h14M13 6l6 6-6 6" />
            @break
        @case('arrow-left')
            <path d="M19 12H5M11 6l-6 6 6 6" />
            @break
        @case('user')
            <circle cx="12" cy="8" r="4" /><path d="M4 21a8 8 0 0 1 16 0" />
            @break
        @case('chart')
            <path d="M3 3v18h18" /><path d="m7 15 4-4 3 3 6-6" />
            @break
        @case('clock')
            <circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" />
            @break
        @case('video')
            <rect x="2.5" y="6" width="13" height="12" rx="2.5" /><path d="m15.5 10.5 6-3.5v10l-6-3.5" />
            @break
        @case('alert')
            <circle cx="12" cy="12" r="9" /><path d="M12 8v4.5M12 16h.01" />
            @break
    @endswitch
</svg>
