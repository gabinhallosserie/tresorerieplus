<x-layout title="Mentions légales">
    <x-page-header title="Mentions légales" />

    <section class="container-page prose-page max-w-3xl py-12">
        <h2>Éditeur du site</h2>
        <p>
            {{ config('site.legal.company') }} au capital de {{ config('site.legal.capital') }} – {{ config('site.legal.rcs') }}<br>
            {{ config('site.legal.address') }}<br>
            Téléphone : {{ config('site.legal.phone') }}<br>
            SIRET : {{ config('site.legal.siret') }} – APE {{ config('site.legal.ape') }}
        </p>

        <h2>Hébergement</h2>
        <p>{{ config('site.legal.host') }}</p>

        <h2>Propriété intellectuelle</h2>
        <p>L'ensemble des contenus de ce site (textes, logos, visuels) est la propriété de l'éditeur, sauf mention contraire. Toute reproduction sans autorisation préalable est interdite.</p>

        <h2>Données personnelles</h2>
        <p>Le traitement de vos données est décrit dans notre <a href="{{ route('privacy') }}" class="underline">politique de confidentialité</a>.</p>
    </section>
</x-layout>
