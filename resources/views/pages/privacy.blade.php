<x-layout title="Politique de confidentialité">
    <x-page-header title="Politique de confidentialité" />

    <section class="container-page prose-page max-w-3xl py-12">
        <h2>Responsable du traitement</h2>
        <p>{{ config('site.legal.company') }}, {{ config('site.legal.address') }}.</p>

        <h2>Données collectées</h2>
        <p>Nous collectons uniquement les données que vous saisissez dans les formulaires du site :</p>
        <ul>
            <li><strong>Formulaire de contact</strong> : nom, e-mail, téléphone (facultatif), objet et message.</li>
            <li><strong>Prise de rendez-vous</strong> : nom, e-mail, téléphone, entreprise (facultatif), activité, type de rendez-vous et message (facultatif).</li>
        </ul>

        <h2>Finalités et base légale</h2>
        <p>Ces données servent exclusivement à répondre à votre demande, à organiser et rappeler votre rendez-vous. Le traitement repose sur votre consentement et sur les mesures précontractuelles prises à votre demande.</p>

        <h2>Destinataires</h2>
        <p>Les données sont utilisées uniquement en interne, par le personnel habilité. Elles ne sont jamais vendues ni transmises à des tiers.</p>

        <h2>Durée de conservation</h2>
        <p>Les messages et rendez-vous sont conservés au maximum 3 ans après le dernier contact, sauf relation commerciale en cours.</p>

        <h2>Cookies</h2>
        <p>Ce site n'utilise aucun cookie publicitaire ni de mesure d'audience. Seuls des cookies techniques, indispensables au fonctionnement des formulaires et de l'espace d'administration, sont déposés. Ils ne nécessitent pas votre consentement.</p>

        <h2>Vos droits</h2>
        <p>
            Vous disposez d'un droit d'accès, de rectification, d'effacement, de limitation et d'opposition sur vos données. Pour les exercer, écrivez à
            <a href="mailto:{{ config('site.legal.privacy_email') }}" class="underline">{{ config('site.legal.privacy_email') }}</a>.
            Vous pouvez également introduire une réclamation auprès de la CNIL (cnil.fr).
        </p>
    </section>
</x-layout>
