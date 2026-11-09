<x-admin-layout title="Rendez-vous">
    @if ($unreadMessages > 0)
        <a href="{{ route('admin.messages.index') }}" class="mb-6 flex items-center justify-between gap-3 rounded-2xl border border-brand-100 bg-brand-50 px-5 py-4 text-sm font-semibold text-brand-700">
            <span class="flex items-center gap-2"><x-icon name="mail" class="size-4" /> {{ $unreadMessages }} message(s) non lu(s)</span>
            <x-icon name="arrow" class="size-4" />
        </a>
    @endif

    <div class="grid gap-6 xl:grid-cols-[1fr_20rem]">
        <section class="card p-4 sm:p-6">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-bold">
                    Semaine du {{ $weekStart->translatedFormat('j F Y') }}
                </h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.dashboard', ['semaine' => $weekStart->subWeek()->toDateString()]) }}" class="btn btn-secondary px-3 py-2" aria-label="Semaine précédente"><x-icon name="arrow-left" class="size-4" /></a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary px-4 py-2">Aujourd'hui</a>
                    <a href="{{ route('admin.dashboard', ['semaine' => $weekStart->addWeek()->toDateString()]) }}" class="btn btn-secondary px-3 py-2" aria-label="Semaine suivante"><x-icon name="arrow" class="size-4" /></a>
                </div>
            </div>

            <div class="grid gap-3 md:grid-cols-7">
                @foreach (range(0, 6) as $offset)
                    @php($day = $weekStart->addDays($offset))
                    <div @class(['min-h-32 rounded-xl border p-2', 'border-brand-500 bg-brand-50/50' => $day->isToday(), 'border-line' => ! $day->isToday()])>
                        <p class="px-1 text-xs font-bold text-ink-soft uppercase">{{ $day->translatedFormat('D j') }}</p>
                        <div class="mt-2 space-y-1.5">
                            @foreach ($weekAppointments->get($day->toDateString(), []) as $appointment)
                                <a href="{{ route('admin.appointments.show', $appointment) }}"
                                   @class(['block rounded-lg px-2 py-1.5 text-xs', 'bg-brand-600 text-white hover:bg-brand-700' => ! $appointment->isCancelled(), 'bg-paper text-ink-soft line-through' => $appointment->isCancelled()])>
                                    <span class="font-bold">{{ $appointment->starts_at->format('H\hi') }}</span>
                                    <span class="block truncate">{{ $appointment->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <aside class="space-y-6">
            <section class="card">
                <h2 class="font-bold">Prochains rendez-vous</h2>
                <ul class="mt-4 divide-y divide-line">
                    @forelse ($upcoming as $appointment)
                        <li>
                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="block py-3 hover:text-brand-600">
                                <span class="block text-sm font-bold capitalize">{{ $appointment->starts_at->translatedFormat('D j M · H\hi') }}</span>
                                <span class="block text-sm text-ink-soft">{{ $appointment->name }} · {{ $appointment->modeLabel() }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="py-3 text-sm text-ink-soft">Aucun rendez-vous à venir.</li>
                    @endforelse
                </ul>
            </section>

            @if ($calendarFeedUrl)
                <section class="card">
                    <h2 class="font-bold">Synchroniser avec mon agenda</h2>
                    <p class="mt-2 text-sm text-ink-soft">Ajoutez cette adresse dans Google Agenda (« À partir de l'URL ») ou Outlook (« S'abonner au calendrier »). Gardez-la privée.</p>
                    <input type="text" readonly value="{{ $calendarFeedUrl }}" class="field-input mt-3 text-xs" onclick="this.select()" aria-label="Adresse du calendrier">
                </section>
            @endif
        </aside>
    </div>
</x-admin-layout>
