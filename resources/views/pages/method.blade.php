<x-layout title="Notre méthode" description="Comment Trésorerie Plus vous aide à interpréter votre situation de trésorerie et à bâtir des prévisions fiables : diagnostic, outil de prévision et suivi par un conseiller.">
    <x-page-header eyebrow="Notre méthode" title="Interpréter votre situation, puis la piloter">
        Un accompagnement concret, pensé pour les dirigeants qui n'ont ni le temps ni l'envie de devenir experts-comptables.
    </x-page-header>

    <section class="container-page py-16">
        <ol class="grid gap-6 lg:grid-cols-3">
            @foreach ([
                ['Premier rendez-vous gratuit', 'Nous faisons le point sur votre activité, vos difficultés et vos projets. Vous repartez avec une première lecture de votre situation de trésorerie.'],
                ['Diagnostic et prévisionnel', 'Votre conseiller construit avec vous un prévisionnel de trésorerie dans notre outil : encaissements, décaissements, échéances, saisonnalité.'],
                ['Suivi dans la durée', 'Vous mettez à jour vos prévisions et votre conseiller vous aide à les interpréter : anticiper un creux, négocier avec la banque, planifier un investissement.'],
            ] as $index => [$stepTitle, $stepText])
                <li class="card p-8">
                    <span class="flex size-10 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">{{ $index + 1 }}</span>
                    <h2 class="mt-6 text-xl font-bold">{{ $stepTitle }}</h2>
                    <p class="mt-3 leading-relaxed text-ink-soft">{{ $stepText }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="bg-white py-16">
        <div class="container-page grid gap-12 lg:grid-cols-2">
            <div>
                <p class="eyebrow">Quels résultats ?</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight">Ce que vous y gagnez</h2>
                <img src="{{ asset('images/outil-prevision.jpg') }}" alt="Capture de l'outil de prévision de trésorerie Trésorerie Plus" width="318" height="314" loading="lazy"
                     class="mt-8 w-64">
            </div>
            <ul class="space-y-4">
                @foreach ([
                    'Anticiper les variations de trésorerie.',
                    'Avoir une vision rapide des postes de dépenses et de gains.',
                    'Déterminer facilement votre besoin de trésorerie et les leviers d\'action possibles.',
                    'Réaliser des simulations d\'actions : embauches, crédits, retards de paiement, investissements…',
                    'Mieux négocier avec vos partenaires financiers.',
                    'Diminuer vos frais bancaires.',
                ] as $result)
                    <li class="flex gap-3">
                        <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-growth-50 text-growth-600"><x-icon name="check" class="size-3.5" /></span>
                        <span class="leading-relaxed text-ink-soft">{{ $result }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="container-page py-16">
        <div class="card flex flex-col gap-6 p-8 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold">Envie de voir où vous en êtes ?</h2>
                <p class="mt-1 text-ink-soft">Testez gratuitement votre trésorerie avec notre outil en ligne.</p>
            </div>
            <a href="{{ config('site.forecast_tool_url') }}" class="btn btn-secondary shrink-0" rel="noopener">Tester ma trésorerie</a>
        </div>
    </section>

    <x-cta-band />
</x-layout>
