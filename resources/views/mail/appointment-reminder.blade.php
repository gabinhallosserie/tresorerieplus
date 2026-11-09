<x-mail::message>
# Rappel de votre rendez-vous

Bonjour {{ $appointment->name }},

Nous vous rappelons votre rendez-vous avec {{ config('site.advisor.name') }} :

<x-mail::panel>
**{{ ucfirst($appointment->starts_at->translatedFormat('l j F Y')) }}** à **{{ $appointment->starts_at->format('H\hi') }}**<br>
{{ $appointment->modeLabel() }}
@if ($appointment->mode === 'office')
<br>{{ config('site.advisor.address_line') }}
@endif
</x-mail::panel>

Pensez à rassembler vos derniers relevés bancaires et, si vous en avez, vos tableaux de suivi : ils permettront de gagner du temps.

<x-mail::button :url="route('booking.show', $appointment->token)">
Gérer mon rendez-vous
</x-mail::button>

À bientôt,<br>
{{ config('site.advisor.name') }}
</x-mail::message>
