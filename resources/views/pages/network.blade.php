<x-layout title="Rejoindre le réseau" description="Professionnels de l'accompagnement des entreprises : rejoignez le premier réseau national de conseillers spécialisés en prévision de trésorerie des TPE.">
    <x-page-header eyebrow="Le réseau" title="Professionnels, rejoignez le réseau Trésorerie Plus">
        Trésorerie Plus est le premier réseau national de conseillers spécialisés dans l'accompagnement en prévision de trésorerie des TPE.
    </x-page-header>

    <section class="container-page grid gap-10 py-16 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="prose-page">
            <h2>Une expertise recherchée par les dirigeants</h2>
            <p>
                Les dirigeants de TPE ont besoin d'un accompagnement de proximité pour anticiper leur trésorerie. En rejoignant le réseau, vous proposez à vos clients une méthode éprouvée et un outil de prévision dédié.
            </p>
            <h2>Ce que le réseau vous apporte</h2>
            <ul>
                <li>Un outil de prévision de trésorerie à utiliser avec vos clients.</li>
                <li>Une méthode d'accompagnement structurée.</li>
                <li>La visibilité d'une marque nationale.</li>
                <li>Les échanges avec les autres conseillers du réseau.</li>
            </ul>
        </div>
        <figure class="card p-4 lg:col-span-2 lg:order-first">
            <img src="{{ asset('images/carte-reseau.png') }}" alt="Carte de France des conseillers du réseau Trésorerie Plus" width="641" height="610" loading="lazy"
                 class="mx-auto w-full max-w-lg">
            <figcaption class="mt-3 text-center text-sm text-ink-soft">Les conseillers du réseau Trésorerie Plus en France</figcaption>
        </figure>

        <aside class="card h-fit p-8">
            <h2 class="text-lg font-bold">Parlons de votre projet</h2>
            <p class="mt-2 text-ink-soft">Présentez-vous en quelques lignes, nous revenons vers vous rapidement.</p>
            <a href="{{ route('contact', ['objet' => 'network']) }}" class="btn btn-primary mt-6 w-full">Envoyer ma candidature</a>
        </aside>
    </section>
</x-layout>
