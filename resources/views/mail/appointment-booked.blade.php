<x-mail::message>
# Nouveau rendez-vous

<x-mail::panel>
**{{ ucfirst($appointment->starts_at->translatedFormat('l j F Y')) }}** de {{ $appointment->starts_at->format('H\hi') }} à {{ $appointment->ends_at->format('H\hi') }}<br>
{{ $appointment->modeLabel() }}
</x-mail::panel>

- **Nom :** {{ $appointment->name }}
- **Entreprise :** {{ $appointment->company ?: '—' }}
- **Activité :** {{ $appointment->activityLabel() }}
- **Téléphone :** {{ $appointment->phone }}
- **E-mail :** {{ $appointment->email }}

@if ($appointment->message)
**Message :**

{{ $appointment->message }}
@endif

<x-mail::button :url="route('admin.appointments.show', $appointment)">
Voir dans l'espace admin
</x-mail::button>
</x-mail::message>
