<x-mail::message>
# {{ $contactMessage->subjectLabel() }}

- **Nom :** {{ $contactMessage->name }}
- **E-mail :** {{ $contactMessage->email }}
- **Téléphone :** {{ $contactMessage->phone ?: '—' }}

<x-mail::panel>
{{ $contactMessage->message }}
</x-mail::panel>

Répondez directement à cet e-mail pour écrire à {{ $contactMessage->name }}.

<x-mail::button :url="route('admin.messages.index')">
Voir les messages
</x-mail::button>
</x-mail::message>
