<x-layout title="Contact" description="Contactez Trésorerie Plus : écrivez-nous en ligne, appelez le {{ config('site.advisor.phone') }} ou réservez directement un rendez-vous gratuit.">
    <x-page-header eyebrow="Contact" title="Écrivez-nous, nous vous répondons rapidement">
        Une question, un besoin d'accompagnement ou l'envie de rejoindre le réseau ? Laissez-nous un message.
    </x-page-header>

    <section class="container-page grid gap-10 py-16 lg:grid-cols-[1.5fr_1fr]">
        <div class="card p-6 sm:p-8">
            @if (session('status'))
                <x-alert class="mb-6">{{ session('status') }}</x-alert>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="relative grid gap-5 sm:grid-cols-2" novalidate>
                @csrf
                <x-honeypot />

                <x-field name="name" label="Nom et prénom" required autocomplete="name" />
                <x-field name="email" label="E-mail" type="email" required autocomplete="email" />
                <x-field name="phone" label="Téléphone" type="tel" autocomplete="tel" />

                <div>
                    <label for="subject" class="field-label">Objet</label>
                    <select id="subject" name="subject" class="field-input" required>
                        @foreach (\App\Models\ContactMessage::SUBJECTS as $value => $label)
                            <option value="{{ $value }}" @selected(old('subject', $selectedSubject) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('subject') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <x-field name="message" label="Votre message" type="textarea" required class="sm:col-span-2" />

                <div class="sm:col-span-2">
                    <label class="flex gap-3 text-sm text-ink-soft">
                        <input type="checkbox" name="consent" value="1" class="mt-0.5 size-4 shrink-0 accent-brand-600" @checked(old('consent')) required>
                        <span>J'accepte que mes données soient utilisées pour répondre à ma demande, conformément à la <a href="{{ route('privacy') }}" class="underline">politique de confidentialité</a>.</span>
                    </label>
                    @error('consent') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <button type="submit" class="btn btn-primary w-full sm:w-auto">
                        <x-icon name="mail" class="size-4" />
                        Envoyer mon message
                    </button>
                </div>
            </form>
        </div>

        <aside class="space-y-6">
            <div class="card">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/patricia-vialaret.jpg') }}" alt="" width="150" height="226" class="h-20 w-16 rounded-xl object-cover object-top">
                    <div>
                        <p class="text-sm font-semibold text-ink-soft">Votre conseillère</p>
                        <p class="mt-1 text-lg font-bold">{{ config('site.advisor.name') }}</p>
                    </div>
                </div>
                <ul class="mt-5 space-y-4 text-sm">
                    <li class="flex gap-3">
                        <x-icon name="phone" class="mt-0.5 size-4 shrink-0 text-brand-600" />
                        <a href="tel:{{ config('site.advisor.phone_link') }}" class="font-semibold hover:text-brand-600">{{ config('site.advisor.phone') }}</a>
                    </li>
                    <li class="flex gap-3">
                        <x-icon name="mail" class="mt-0.5 size-4 shrink-0 text-brand-600" />
                        <a href="mailto:{{ config('site.advisor.email') }}" class="break-all hover:text-brand-600">{{ config('site.advisor.email') }}</a>
                    </li>
                    <li class="flex gap-3">
                        <x-icon name="pin" class="mt-0.5 size-4 shrink-0 text-brand-600" />
                        <span>{{ config('site.advisor.street') }}<br>{{ config('site.advisor.city') }}</span>
                    </li>
                </ul>
            </div>
            <div class="rounded-2xl bg-brand-600 p-6 text-white">
                <p class="text-lg font-bold">Vous préférez en parler ?</p>
                <p class="mt-1 text-white/80">Réservez directement un rendez-vous gratuit.</p>
                <a href="{{ route('booking.index') }}" class="btn mt-4 bg-white text-ink hover:bg-brand-50">Voir les créneaux</a>
            </div>
        </aside>
    </section>
</x-layout>
