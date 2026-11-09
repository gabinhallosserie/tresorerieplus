<x-layout>
    <section class="relative overflow-hidden bg-white">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(60rem_30rem_at_85%_-10%,var(--color-brand-50),transparent)]"></div>
        <div class="container-page relative grid items-center gap-12 py-16 lg:grid-cols-[1.1fr_0.9fr] lg:py-24">
            <div>
                <p class="eyebrow">Conseil en prévision de trésorerie · TPE & PME</p>
                <h1 class="mt-4 text-4xl leading-[1.1] font-extrabold tracking-tight text-balance sm:text-5xl lg:text-6xl">
                    Anticipez votre trésorerie au lieu de la <span class="text-brand-600">subir</span>.
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-ink-soft">
                    Dirigeants de TPE, artisans, commerçants, professions libérales : un conseiller dédié et un outil de prévision pour piloter votre entreprise sereinement et convaincre vos partenaires financiers.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <a href="{{ route('booking.index') }}" class="btn btn-primary px-6 py-3.5 text-base">
                        <x-icon name="calendar" class="size-5" />
                        Prendre un rendez-vous gratuit
                    </a>
                    <a href="{{ config('site.forecast_tool_url') }}" class="btn btn-secondary px-6 py-3.5 text-base" rel="noopener">
                        Tester gratuitement ma trésorerie
                    </a>
                </div>
                <p class="mt-5 text-sm text-ink-soft">
                    Ou appelez directement le
                    <a href="tel:{{ config('site.advisor.phone_link') }}" class="font-semibold text-ink underline decoration-brand-500/40 underline-offset-4 hover:decoration-brand-500">{{ config('site.advisor.phone') }}</a>
                </p>
            </div>

            <figure class="card mx-auto w-full max-w-md p-8 text-center shadow-xl shadow-brand-700/5">
                <img src="{{ asset('images/patricia-vialaret.jpg') }}" alt="Patricia Vialaret, conseillère Trésorerie Plus" width="150" height="226"
                     class="mx-auto h-40 w-32 rounded-2xl object-cover object-top">
                <figcaption class="mt-5">
                    <p class="text-lg font-bold">{{ config('site.advisor.name') }}</p>
                    <p class="text-sm text-ink-soft">Votre conseillère Trésorerie Plus à Millau</p>
                </figcaption>
                <div class="mt-6 grid gap-2 text-left text-sm">
                    <p class="flex items-center gap-3 rounded-xl bg-paper px-4 py-3">
                        <x-icon name="pin" class="size-4 shrink-0 text-brand-600" />
                        Au cabinet, par téléphone ou en visio
                    </p>
                    <p class="flex items-center gap-3 rounded-xl bg-paper px-4 py-3">
                        <x-icon name="calendar" class="size-4 shrink-0 text-brand-600" />
                        Premier rendez-vous gratuit
                    </p>
                </div>
            </figure>
        </div>
    </section>

    <section class="border-y border-line bg-paper">
        <div class="container-page py-6 text-center text-sm font-semibold text-ink-soft">
            Premier réseau national de conseillers spécialisés dans l'accompagnement en prévision de trésorerie des TPE
        </div>
    </section>

    <section class="container-page py-20">
        <div class="max-w-2xl">
            <p class="eyebrow">Vous vous reconnaissez ?</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Votre entreprise tourne, et pourtant la trésorerie reste tendue.</h2>
        </div>
        <ul class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ([
                'Votre entreprise génère de l\'activité et pourtant vous avez des difficultés de trésorerie.',
                'Vous avez du mal à financer votre croissance.',
                'Vous avez besoin d\'arguments pour vos partenaires financiers.',
                'Vous avez du mal à rémunérer votre travail à sa juste valeur.',
                'Vous avez du mal à avoir une vision globale et à anticiper vos variations de trésorerie.',
            ] as $pain)
                <li class="card flex gap-4">
                    <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600"><x-icon name="check" class="size-4" /></span>
                    <p class="leading-relaxed text-ink-soft">{{ $pain }}</p>
                </li>
            @endforeach
            <li class="flex flex-col justify-center rounded-2xl bg-brand-600 p-6 text-white">
                <p class="text-lg font-bold">Vous n'êtes pas seul.</p>
                <p class="mt-1 text-white/80">Faisons le point ensemble, sans engagement.</p>
                <a href="{{ route('booking.index') }}" class="mt-4 inline-flex items-center gap-2 font-semibold">Réserver un créneau <x-icon name="arrow" class="size-4" /></a>
            </li>
        </ul>
    </section>

    <section class="bg-white py-20">
        <div class="container-page">
            <div class="max-w-2xl">
                <p class="eyebrow">Notre accompagnement</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Deux piliers pour reprendre la main</h2>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-2">
                <article class="card p-8">
                    <span class="flex size-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600"><x-icon name="user" class="size-6" /></span>
                    <h3 class="mt-6 text-xl font-bold">Un conseiller à vos côtés</h3>
                    <p class="mt-3 leading-relaxed text-ink-soft">
                        Un interlocuteur unique, à votre écoute, qui connaît les réalités des petites entreprises. Il vous aide à interpréter votre situation, à préparer vos rendez-vous bancaires et à prendre les bonnes décisions au bon moment.
                    </p>
                </article>
                <article class="card p-8">
                    <span class="flex size-12 items-center justify-center rounded-2xl bg-growth-50 text-growth-600"><x-icon name="chart" class="size-6" /></span>
                    <h3 class="mt-6 text-xl font-bold">Un outil de prévision de trésorerie</h3>
                    <p class="mt-3 leading-relaxed text-ink-soft">
                        Un tableau de bord simple pour visualiser vos encaissements et décaissements à venir, repérer les tensions avant qu'elles n'arrivent et présenter des prévisions claires à vos partenaires.
                    </p>
                    <img src="{{ asset('images/outil-prevision.jpg') }}" alt="Capture de l'outil de prévision de trésorerie Trésorerie Plus" width="318" height="314" loading="lazy"
                         class="mx-auto mt-6 w-56">

                </article>
            </div>
            <div class="mt-8">
                <a href="{{ route('method') }}" class="inline-flex items-center gap-2 font-semibold text-brand-600 hover:text-brand-700">Découvrir notre méthode <x-icon name="arrow" class="size-4" /></a>
            </div>
        </div>
    </section>

    <section class="container-page py-20">
        <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
            <div>
                <p class="eyebrow">Pour qui ?</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">En création, en difficulté ou en développement</h2>
                <p class="mt-4 leading-relaxed text-ink-soft">
                    À chaque étape, il faut convaincre : les banques, les fournisseurs, l'administration. Montrer que vous maîtrisez vos prévisions de trésorerie fait toute la différence.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                @foreach (['Dirigeants de TPE', 'Artisans', 'Commerçants', 'Professions libérales', 'Créateurs d\'entreprise', 'PME en croissance'] as $audience)
                    <div class="rounded-2xl border border-line bg-white px-4 py-5 text-center text-sm font-semibold">{{ $audience }}</div>
                @endforeach
            </div>
        </div>
    </section>

    @if (filled(config('site.testimonials')))
        <section class="bg-white py-20">
            <div class="container-page">
                <p class="eyebrow">Témoignages</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Ils ont repris la main sur leur trésorerie</h2>
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @foreach (config('site.testimonials') as $testimonial)
                        <figure class="card flex flex-col">
                            <blockquote class="flex-1 leading-relaxed text-ink-soft">« {{ $testimonial['quote'] }} »</blockquote>
                            <figcaption class="mt-6 text-sm">
                                <span class="font-bold">{{ $testimonial['author'] }}</span><br>
                                <span class="text-ink-soft">{{ $testimonial['role'] }}</span>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band />
</x-layout>
