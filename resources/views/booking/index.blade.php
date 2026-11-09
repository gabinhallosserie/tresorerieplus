<x-layout title="Prendre rendez-vous" description="Réservez en ligne votre premier rendez-vous gratuit avec un conseiller Trésorerie Plus : au cabinet, par téléphone ou en visioconférence.">
    <x-page-header eyebrow="Rendez-vous gratuit" title="Choisissez votre créneau">
        Premier échange gratuit et sans engagement de {{ $slotMinutes }} minutes avec {{ config('site.advisor.name') }}, au cabinet à Millau, par téléphone ou en visioconférence.
    </x-page-header>

    <section class="container-page py-12">
        @if (session('warning'))
            <x-alert type="warning" class="mb-6">{{ session('warning') }}</x-alert>
        @endif
        @error('starts_at')
            <x-alert type="warning" class="mb-6">{{ $message }}</x-alert>
        @enderror

        <div class="mb-6 flex items-center justify-between gap-4">
            <p class="font-bold">
                Du {{ $days->first()->translatedFormat('j F') }} au {{ $days->last()->translatedFormat('j F Y') }}
            </p>
            <div class="flex gap-2">
                @if ($previousStart)
                    <a href="{{ route('booking.index', ['du' => $previousStart->toDateString()]) }}" class="btn btn-secondary px-3" aria-label="Semaine précédente">
                        <x-icon name="arrow-left" class="size-4" />
                    </a>
                @endif
                @if ($nextStart)
                    <a href="{{ route('booking.index', ['du' => $nextStart->toDateString()]) }}" class="btn btn-secondary px-4">
                        Semaine suivante <x-icon name="arrow" class="size-4" />
                    </a>
                @endif
            </div>
        </div>

        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-7">
            @foreach ($days as $day)
                @php($daySlots = $slots[$day->toDateString()] ?? [])
                <div @class(['rounded-2xl border p-3', 'border-line bg-white' => $daySlots, 'border-dashed border-line bg-transparent' => ! $daySlots])>
                    <p class="px-1 text-sm font-bold capitalize">{{ $day->translatedFormat('l') }}</p>
                    <p class="px-1 text-xs text-ink-soft">{{ $day->translatedFormat('j F') }}</p>
                    <div class="mt-3 grid grid-cols-3 gap-2 lg:grid-cols-1">
                        @forelse ($daySlots as $slot)
                            <a href="{{ route('booking.create', ['creneau' => $slot->format('Y-m-d H:i')]) }}"
                               class="rounded-xl border border-brand-100 bg-brand-50 px-2 py-2.5 text-center text-sm font-semibold text-brand-700 transition hover:border-brand-500 hover:bg-brand-600 hover:text-white"
                               aria-label="Réserver le {{ $slot->translatedFormat('l j F à H\hi') }}">
                                {{ $slot->format('H\hi') }}
                            </a>
                        @empty
                            <p class="col-span-3 px-1 py-2 text-xs text-ink-soft lg:col-span-1">Aucun créneau</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        @if (empty($slots))
            <div class="mt-8">
                <x-alert type="warning">
                    Aucun créneau n'est disponible sur cette période.
                    @if ($nextStart) Essayez la semaine suivante ou @else Vous pouvez @endif
                    nous appeler au <a class="underline" href="tel:{{ config('site.advisor.phone_link') }}">{{ config('site.advisor.phone') }}</a>.
                </x-alert>
            </div>
        @endif

        <p class="mt-8 text-sm text-ink-soft">
            Aucun horaire ne vous convient ? Appelez le <a class="font-semibold text-ink underline" href="tel:{{ config('site.advisor.phone_link') }}">{{ config('site.advisor.phone') }}</a>
            ou <a class="font-semibold text-ink underline" href="{{ route('contact') }}">écrivez-nous</a>.
        </p>
    </section>
</x-layout>
