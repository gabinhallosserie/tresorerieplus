<x-layout title="Mon rendez-vous">
    <x-slot:head><meta name="robots" content="noindex"></x-slot:head>

    <section class="container-page max-w-2xl py-16">
        @if (session('status'))
            <x-alert class="mb-6">{{ session('status') }}</x-alert>
        @endif

        <div class="card p-8 text-center sm:p-10">
            @if ($appointment->isCancelled())
                <span class="mx-auto flex size-14 items-center justify-center rounded-full bg-red-50 text-red-700"><x-icon name="alert" class="size-7" /></span>
                <h1 class="mt-6 text-2xl font-extrabold">Rendez-vous annulé</h1>
            @elseif (session('booked'))
                <span class="mx-auto flex size-14 items-center justify-center rounded-full bg-growth-50 text-growth-600"><x-icon name="check" class="size-7" /></span>
                <h1 class="mt-6 text-2xl font-extrabold">C'est réservé, merci {{ $appointment->name }} !</h1>
                <p class="mt-2 text-ink-soft">Un e-mail de confirmation vient de vous être envoyé à {{ $appointment->email }}.</p>
            @else
                <span class="mx-auto flex size-14 items-center justify-center rounded-full bg-brand-50 text-brand-600"><x-icon name="calendar" class="size-7" /></span>
                <h1 class="mt-6 text-2xl font-extrabold">Votre rendez-vous</h1>
            @endif

            <dl class="mt-8 grid gap-4 rounded-2xl bg-paper p-6 text-left sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold text-ink-soft uppercase">Date</dt>
                    <dd class="mt-1 font-bold capitalize">{{ $appointment->starts_at->translatedFormat('l j F Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-ink-soft uppercase">Horaire</dt>
                    <dd class="mt-1 font-bold">{{ $appointment->starts_at->format('H\hi') }} – {{ $appointment->ends_at->format('H\hi') }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold text-ink-soft uppercase">Type</dt>
                    <dd class="mt-1 font-bold">{{ $appointment->modeLabel() }}</dd>
                    @if ($appointment->mode === 'office')
                        <dd class="text-sm text-ink-soft">{{ config('site.advisor.address_line') }}</dd>
                    @endif
                </div>
            </dl>

            @if ($appointment->isUpcoming())
                <form method="POST" action="{{ route('booking.cancel', $appointment->token) }}" class="mt-8" onsubmit="return confirm('Confirmez-vous l\'annulation de ce rendez-vous ?')">
                    @csrf
                    <button type="submit" class="btn btn-danger">Annuler mon rendez-vous</button>
                </form>
            @elseif ($appointment->isCancelled())
                <a href="{{ route('booking.index') }}" class="btn btn-primary mt-8">Choisir un nouveau créneau</a>
            @endif

            <p class="mt-8 text-sm text-ink-soft">
                Une question ? {{ config('site.advisor.name') }} – <a class="underline" href="tel:{{ config('site.advisor.phone_link') }}">{{ config('site.advisor.phone') }}</a>
            </p>
        </div>
    </section>
</x-layout>
