<x-layout title="Confirmer mon rendez-vous">
    <section class="container-page py-12">
        <a href="{{ route('booking.index', ['du' => $start->toDateString()]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-ink-soft hover:text-ink">
            <x-icon name="arrow-left" class="size-4" /> Changer de créneau
        </a>

        <div class="mt-6 grid gap-8 lg:grid-cols-[1fr_1.6fr]">
            <aside class="h-fit rounded-2xl bg-ink p-8 text-white">
                <p class="text-sm font-semibold text-white/60">Votre rendez-vous</p>
                <p class="mt-2 text-2xl font-extrabold capitalize">{{ $start->translatedFormat('l j F') }}</p>
                <p class="mt-1 flex items-center gap-2 text-white/80">
                    <x-icon name="clock" class="size-4" />
                    {{ $start->format('H\hi') }} – {{ $end->format('H\hi') }}
                </p>
                <div class="mt-6 flex items-center gap-3">
                    <img src="{{ asset('images/patricia-vialaret.jpg') }}" alt="" width="150" height="226" class="h-14 w-11 rounded-lg object-cover object-top">
                    <p class="text-sm text-white/70">avec {{ config('site.advisor.name') }}, conseillère Trésorerie Plus.</p>
                </div>
            </aside>

            <div class="card p-6 sm:p-8">
                <h1 class="text-2xl font-extrabold">Vos coordonnées</h1>
                <p class="mt-1 text-ink-soft">Vous recevrez une confirmation par e-mail.</p>

                <form method="POST" action="{{ route('booking.store') }}" class="relative mt-8 grid gap-5 sm:grid-cols-2" novalidate>
                    @csrf
                    <x-honeypot />
                    <input type="hidden" name="starts_at" value="{{ $start->format('Y-m-d H:i') }}">

                    <fieldset class="sm:col-span-2">
                        <legend class="field-label">Type de rendez-vous</legend>
                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach (\App\Models\Appointment::MODES as $value => $label)
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-line px-4 py-3 text-sm font-semibold has-checked:border-brand-500 has-checked:bg-brand-50">
                                    <input type="radio" name="mode" value="{{ $value }}" class="accent-brand-600" @checked(old('mode', 'office') === $value)>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                        @error('mode') <p class="field-error">{{ $message }}</p> @enderror
                    </fieldset>

                    <x-field name="name" label="Nom et prénom" required autocomplete="name" />
                    <x-field name="company" label="Entreprise" autocomplete="organization" />
                    <x-field name="email" label="E-mail" type="email" required autocomplete="email" />
                    <x-field name="phone" label="Téléphone" type="tel" required autocomplete="tel" />

                    <div class="sm:col-span-2">
                        <label for="activity" class="field-label">Votre activité</label>
                        <select id="activity" name="activity" class="field-input" required>
                            @foreach (\App\Models\Appointment::ACTIVITIES as $value => $label)
                                <option value="{{ $value }}" @selected(old('activity') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('activity') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <x-field name="message" label="En quelques mots, votre besoin" type="textarea" class="sm:col-span-2" rows="3" />

                    <div class="sm:col-span-2">
                        <label class="flex gap-3 text-sm text-ink-soft">
                            <input type="checkbox" name="consent" value="1" class="mt-0.5 size-4 shrink-0 accent-brand-600" @checked(old('consent')) required>
                            <span>J'accepte que mes données soient utilisées pour organiser ce rendez-vous, conformément à la <a href="{{ route('privacy') }}" class="underline">politique de confidentialité</a>.</span>
                        </label>
                        @error('consent') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" class="btn btn-primary w-full py-3.5 text-base">
                            <x-icon name="check" class="size-5" />
                            Confirmer mon rendez-vous
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layout>
