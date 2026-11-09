<x-admin-layout title="Messages de contact">
    <div class="space-y-4">
        @forelse ($messages as $contactMessage)
            <article @class(['card', 'border-brand-500' => ! $contactMessage->read_at])>
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-bold">
                            {{ $contactMessage->name }}
                            @unless ($contactMessage->read_at)
                                <span class="ml-2 rounded-full bg-brand-600 px-2 py-0.5 text-xs text-white">Nouveau</span>
                            @endunless
                        </p>
                        <p class="text-sm text-ink-soft">
                            {{ $contactMessage->subjectLabel() }} · {{ $contactMessage->created_at->translatedFormat('j F Y à H\hi') }}
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="mailto:{{ $contactMessage->email }}?subject={{ rawurlencode('Re : '.$contactMessage->subjectLabel()) }}" class="btn btn-primary px-4 py-2">Répondre</a>
                        @unless ($contactMessage->read_at)
                            <form method="POST" action="{{ route('admin.messages.read', $contactMessage) }}">
                                @csrf
                                <button type="submit" class="btn btn-secondary px-4 py-2">Marquer comme lu</button>
                            </form>
                        @endunless
                    </div>
                </div>
                <p class="mt-4 whitespace-pre-line">{{ $contactMessage->message }}</p>
                <p class="mt-4 text-sm text-ink-soft">
                    <a class="underline" href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a>
                    @if ($contactMessage->phone)
                        · <a class="underline" href="tel:{{ $contactMessage->phone }}">{{ $contactMessage->phone }}</a>
                    @endif
                </p>
            </article>
        @empty
            <p class="card text-ink-soft">Aucun message pour le moment.</p>
        @endforelse

        {{ $messages->links() }}
    </div>
</x-admin-layout>
