<x-admin-layout :title="'Rendez-vous de '.$appointment->name">
    <a href="{{ route('admin.dashboard', ['semaine' => $appointment->starts_at->toDateString()]) }}" class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-ink-soft hover:text-ink">
        <x-icon name="arrow-left" class="size-4" /> Retour au calendrier
    </a>

    <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
        <section class="card">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-xl font-extrabold capitalize">{{ $appointment->starts_at->translatedFormat('l j F Y') }}</p>
                @if ($appointment->isCancelled())
                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">Annulé le {{ $appointment->cancelled_at->format('d/m/Y') }}</span>
                @else
                    <span class="rounded-full bg-growth-50 px-3 py-1 text-xs font-bold text-growth-600">Confirmé</span>
                @endif
            </div>
            <p class="mt-1 text-ink-soft">{{ $appointment->starts_at->format('H\hi') }} – {{ $appointment->ends_at->format('H\hi') }} · {{ $appointment->modeLabel() }}</p>

            <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                @foreach ([
                    'Nom' => $appointment->name,
                    'Entreprise' => $appointment->company ?: '—',
                    'Activité' => $appointment->activityLabel(),
                    'Réservé le' => $appointment->created_at->format('d/m/Y à H\hi'),
                ] as $label => $value)
                    <div>
                        <dt class="text-xs font-semibold text-ink-soft uppercase">{{ $label }}</dt>
                        <dd class="mt-1 font-semibold">{{ $value }}</dd>
                    </div>
                @endforeach
                <div>
                    <dt class="text-xs font-semibold text-ink-soft uppercase">Téléphone</dt>
                    <dd class="mt-1 font-semibold"><a class="text-brand-600 underline" href="tel:{{ $appointment->phone }}">{{ $appointment->phone }}</a></dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-ink-soft uppercase">E-mail</dt>
                    <dd class="mt-1 font-semibold break-all"><a class="text-brand-600 underline" href="mailto:{{ $appointment->email }}">{{ $appointment->email }}</a></dd>
                </div>
            </dl>

            @if ($appointment->message)
                <div class="mt-6 rounded-xl bg-paper p-4">
                    <p class="text-xs font-semibold text-ink-soft uppercase">Message du client</p>
                    <p class="mt-2 whitespace-pre-line">{{ $appointment->message }}</p>
                </div>
            @endif
        </section>

        <div class="space-y-6">
            <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}" class="card">
                @csrf
                @method('PATCH')
                <label for="admin_note" class="field-label">Note interne</label>
                <textarea id="admin_note" name="admin_note" rows="5" class="field-input">{{ old('admin_note', $appointment->admin_note) }}</textarea>
                <button type="submit" class="btn btn-secondary mt-3">Enregistrer la note</button>
            </form>

            @if ($appointment->isUpcoming())
                <form method="POST" action="{{ route('admin.appointments.cancel', $appointment) }}" class="card" onsubmit="return confirm('Annuler ce rendez-vous ?')">
                    @csrf
                    <p class="font-bold">Annuler le rendez-vous</p>
                    <p class="mt-1 text-sm text-ink-soft">Le créneau redeviendra disponible en ligne.</p>
                    <label class="mt-4 flex items-center gap-2 text-sm">
                        <input type="checkbox" name="notify_client" value="1" checked class="accent-brand-600">
                        Prévenir le client par e-mail
                    </label>
                    <button type="submit" class="btn btn-danger mt-4">Annuler le rendez-vous</button>
                </form>
            @endif
        </div>
    </div>
</x-admin-layout>
