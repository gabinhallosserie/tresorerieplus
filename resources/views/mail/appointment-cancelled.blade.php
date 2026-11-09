<x-mail::message>
@if ($forAdvisor)
# Rendez-vous annulé

{{ $appointment->name }} a annulé son rendez-vous du **{{ $appointment->starts_at->translatedFormat('l j F Y à H\hi') }}**. Le créneau est de nouveau proposé en ligne.
@else
# Votre rendez-vous est annulé

Bonjour {{ $appointment->name }},

Votre rendez-vous du **{{ $appointment->starts_at->translatedFormat('l j F Y à H\hi') }}** a bien été annulé.

Vous pouvez reprendre rendez-vous à tout moment :

<x-mail::button :url="route('booking.index')">
Choisir un nouveau créneau
</x-mail::button>

{{ config('site.advisor.name') }}<br>
{{ config('site.advisor.phone') }}
@endif
</x-mail::message>
