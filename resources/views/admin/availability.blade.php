<x-admin-layout title="Disponibilités">
    <div class="grid gap-6 xl:grid-cols-[1.5fr_1fr]">
        <form method="POST" action="{{ route('admin.availability.update') }}" class="card space-y-8">
            @csrf
            @method('PUT')

            <section>
                <h2 class="text-lg font-bold">Horaires proposés aux clients</h2>
                <p class="mt-1 text-sm text-ink-soft">Deux plages maximum par jour (par exemple matin et après-midi). Laissez vide pour un jour fermé.</p>

                <div class="mt-5 divide-y divide-line">
                    @foreach (\App\Models\OpeningHour::WEEKDAYS as $weekday => $dayName)
                        @php($dayHours = $openingHours->get($weekday, collect())->values())
                        <div class="grid items-center gap-3 py-3 sm:grid-cols-[7rem_1fr_1fr]">
                            <p class="font-semibold">{{ $dayName }}</p>
                            @foreach ([0, 1] as $rangeIndex)
                                @php($range = $dayHours->get($rangeIndex))
                                <div class="flex items-center gap-2">
                                    <input type="time" step="900" name="hours[{{ $weekday }}][{{ $rangeIndex }}][starts_at]"
                                           value="{{ old("hours.$weekday.$rangeIndex.starts_at", $range ? substr($range->starts_at, 0, 5) : '') }}"
                                           class="field-input px-3 py-2" aria-label="{{ $dayName }}, plage {{ $rangeIndex + 1 }}, début">
                                    <span class="text-ink-soft">à</span>
                                    <input type="time" step="900" name="hours[{{ $weekday }}][{{ $rangeIndex }}][ends_at]"
                                           value="{{ old("hours.$weekday.$rangeIndex.ends_at", $range ? substr($range->ends_at, 0, 5) : '') }}"
                                           class="field-input px-3 py-2" aria-label="{{ $dayName }}, plage {{ $rangeIndex + 1 }}, fin">
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </section>

            <section>
                <h2 class="text-lg font-bold">Règles de réservation</h2>
                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="slot_minutes" class="field-label">Durée d'un rendez-vous</label>
                        <select id="slot_minutes" name="slot_minutes" class="field-input">
                            @foreach ([30, 45, 60, 90, 120] as $minutes)
                                <option value="{{ $minutes }}" @selected((int) old('slot_minutes', $settings->slot_minutes) === $minutes)>{{ $minutes }} minutes</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="buffer_minutes" class="field-label">Pause entre deux rendez-vous (min)</label>
                        <input id="buffer_minutes" name="buffer_minutes" type="number" min="0" max="120" step="5" value="{{ old('buffer_minutes', $settings->buffer_minutes) }}" class="field-input">
                    </div>
                    <div>
                        <label for="min_notice_hours" class="field-label">Délai minimum avant un rendez-vous (heures)</label>
                        <input id="min_notice_hours" name="min_notice_hours" type="number" min="0" max="336" value="{{ old('min_notice_hours', $settings->min_notice_hours) }}" class="field-input">
                    </div>
                    <div>
                        <label for="max_days_ahead" class="field-label">Réservation possible jusqu'à (jours)</label>
                        <input id="max_days_ahead" name="max_days_ahead" type="number" min="1" max="365" value="{{ old('max_days_ahead', $settings->max_days_ahead) }}" class="field-input">
                    </div>
                </div>
            </section>

            <button type="submit" class="btn btn-primary">Enregistrer les disponibilités</button>
        </form>

        <section class="card h-fit">
            <h2 class="text-lg font-bold">Congés et fermetures</h2>
            <p class="mt-1 text-sm text-ink-soft">Aucun créneau ne sera proposé sur ces dates.</p>

            <ul class="mt-5 divide-y divide-line">
                @forelse ($closedPeriods as $closedPeriod)
                    <li class="flex items-center justify-between gap-3 py-3">
                        <div class="text-sm">
                            <p class="font-semibold">
                                @if ($closedPeriod->starts_on->equalTo($closedPeriod->ends_on))
                                    {{ $closedPeriod->starts_on->translatedFormat('j F Y') }}
                                @else
                                    Du {{ $closedPeriod->starts_on->translatedFormat('j F') }} au {{ $closedPeriod->ends_on->translatedFormat('j F Y') }}
                                @endif
                            </p>
                            @if ($closedPeriod->reason)
                                <p class="text-ink-soft">{{ $closedPeriod->reason }}</p>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('admin.closed-periods.destroy', $closedPeriod) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-semibold text-red-700 hover:underline">Supprimer</button>
                        </form>
                    </li>
                @empty
                    <li class="py-3 text-sm text-ink-soft">Aucune fermeture prévue.</li>
                @endforelse
            </ul>

            <form method="POST" action="{{ route('admin.closed-periods.store') }}" class="mt-6 space-y-4 border-t border-line pt-6">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="starts_on" class="field-label">Du</label>
                        <input id="starts_on" name="starts_on" type="date" value="{{ old('starts_on') }}" class="field-input" required>
                    </div>
                    <div>
                        <label for="ends_on" class="field-label">Au</label>
                        <input id="ends_on" name="ends_on" type="date" value="{{ old('ends_on') }}" class="field-input" required>
                    </div>
                </div>
                <div>
                    <label for="reason" class="field-label">Motif <span class="font-normal text-ink-soft">(facultatif)</span></label>
                    <input id="reason" name="reason" type="text" value="{{ old('reason') }}" class="field-input" placeholder="Congés d'été">
                </div>
                <button type="submit" class="btn btn-secondary">Ajouter la fermeture</button>
            </form>
        </section>
    </div>
</x-admin-layout>
