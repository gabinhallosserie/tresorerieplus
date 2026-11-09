<section class="container-page">
    <div class="relative overflow-hidden rounded-3xl bg-ink px-6 py-12 text-white sm:px-12 sm:py-16">
        <div class="pointer-events-none absolute -top-24 -right-24 size-72 rounded-full bg-brand-500/30 blur-3xl"></div>
        <div class="relative grid gap-8 lg:grid-cols-[1.4fr_1fr] lg:items-center">
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Ne restez plus seul face à vos problèmes de trésorerie.</h2>
                <p class="mt-4 max-w-xl text-white/75">Le premier rendez-vous est gratuit et sans engagement. Choisissez directement un créneau qui vous convient.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row lg:flex-col lg:items-end">
                <a href="{{ route('booking.index') }}" class="btn bg-white px-6 py-3.5 text-base text-ink hover:bg-brand-50">
                    <x-icon name="calendar" class="size-5" />
                    Voir les créneaux disponibles
                </a>
                <a href="tel:{{ config('site.advisor.phone_link') }}" class="btn border border-white/25 px-6 py-3.5 text-base text-white hover:bg-white/10">
                    <x-icon name="phone" class="size-5" />
                    {{ config('site.advisor.phone') }}
                </a>
            </div>
        </div>
    </div>
</section>
