<x-mail::message>
# Votre rendez-vous est confirmé

Bonjour {{ $appointment->name }},

Merci pour votre confiance. Votre rendez-vous avec {{ config('site.advisor.name') }} est bien enregistré :

<x-mail::panel>
**{{ ucfirst($appointment->starts_at->translatedFormat('l j F Y')) }}** de **{{ $appointment->starts_at->format('H\hi') }}** à **{{ $appointment->ends_at->format('H\hi') }}**<br>
{{ $appointment->modeLabel() }}
@if ($appointment->mode === 'office')
<br>{{ config('site.advisor.address_line') }}
@elseif ($appointment->mode === 'phone')
<br>Nous vous appellerons au {{ $appointment->phone }}.
@else
<br>Le lien de visioconférence vous sera envoyé avant le rendez-vous.
@endif
</x-mail::panel>

Le fichier joint vous permet d'ajouter ce rendez-vous à votre agenda.

Un empêchement ? Vous pouvez annuler votre rendez-vous en ligne :

<x-mail::button :url="route('booking.show', $appointment->token)">
Gérer mon rendez-vous
</x-mail::button>

À très bientôt,<br>
{{ config('site.advisor.name') }}<br>
{{ config('site.advisor.phone') }}
</x-mail::message>
