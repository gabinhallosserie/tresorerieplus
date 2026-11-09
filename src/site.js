// Données du site et fragments HTML partagés entre les pages.
// Dans les fichiers .html, on les appelle avec la syntaxe {{ fonction("arg", ...) }}
// (voir le plugin « partials » dans vite.config.js).

export const advisor = {
    name: 'Patricia Vialaret',
    phone: '06 87 04 88 68',
    phoneLink: '+33687048868',
    email: 'p.vialaret@tresorerie-plus.fr',
    street: '144 avenue Jean Jaurès',
    city: '12100 Millau',
};

export const legal = {
    company: 'SARL BCL',
    capital: '7 500 €',
    rcs: 'RCS Rodez',
    siret: '793 199 043 00019',
    ape: '6201Z',
    address: '144 avenue Jean Jaurès, 12100 Millau',
    phone: '05 81 63 05 25',
    privacyEmail: 'assistance@tresorerie-plus.fr',
    host: 'Netlify, Inc. – San Francisco, Californie, États-Unis – www.netlify.com',
};

export const forecastToolUrl = 'https://www.tresorerie-plus-prog.fr/Tresorerie_Plus_Prog/TRESORERIE_PLUS_PROG_WEB/FR/index.awp';

export const routes = {
    home: '/',
    method: '/methode/',
    network: '/rejoindre-le-reseau/',
    contact: '/contact/',
    booking: '/rendez-vous/',
    legal: '/mentions-legales/',
    privacy: '/confidentialite/',
};

const navigation = [
    ['home', 'Accueil'],
    ['method', 'Notre méthode'],
    ['network', 'Le réseau'],
    ['contact', 'Contact'],
];

const defaultDescription = 'Trésorerie Plus accompagne les dirigeants de TPE, artisans, commerçants et professions libérales pour anticiper leurs variations de trésorerie au lieu de les subir. Premier rendez-vous gratuit.';

// URL publique du site : Netlify la fournit au build via la variable URL.
const siteUrl = (process.env.SITE_URL || process.env.URL || '').replace(/\/$/, '');

const icons = {
    phone: '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z" />',
    menu: '<path d="M4 7h16M4 12h16M4 17h16" />',
    calendar: '<rect x="3" y="4.5" width="18" height="16.5" rx="2.5" /><path d="M16 2.5v4M8 2.5v4M3 10h18" />',
    mail: '<rect x="2.5" y="4.5" width="19" height="15" rx="2.5" /><path d="m3 6.5 9 6.5 9-6.5" />',
    pin: '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" /><circle cx="12" cy="10" r="3" />',
    check: '<path d="M20 6 9 17l-5-5" />',
    arrow: '<path d="M5 12h14M13 6l6 6-6 6" />',
    user: '<circle cx="12" cy="8" r="4" /><path d="M4 21a8 8 0 0 1 16 0" />',
    chart: '<path d="M3 3v18h18" /><path d="m7 15 4-4 3 3 6-6" />',
    clock: '<circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" />',
    video: '<rect x="2.5" y="6" width="13" height="12" rx="2.5" /><path d="m15.5 10.5 6-3.5v10l-6-3.5" />',
    alert: '<circle cx="12" cy="12" r="9" /><path d="M12 8v4.5M12 16h.01" />',
};

export function icon(name, className = '') {
    if (!icons[name]) {
        throw new Error(`Icône inconnue : ${name}`);
    }

    return `<svg aria-hidden="true" class="${className}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${icons[name]}</svg>`;
}

function logo(inverted = false) {
    const classes = inverted ? 'inline-flex items-center rounded-lg bg-white px-3 py-2' : 'inline-flex items-center';

    return `<span class="${classes}"><img src="/images/logo-tresorerie-plus.png" alt="Trésorerie Plus" width="299" height="40" class="h-7 w-auto sm:h-8"></span>`;
}

const page = { path: '/' };

export const helpers = {
    route: (name) => routes[name],
    advisor: (key) => advisor[key],
    legal: (key) => legal[key],
    forecastUrl: () => forecastToolUrl,
    icon,

    head(title = null, description = defaultDescription) {
        const fullTitle = title ? `${title} · Trésorerie Plus` : 'Trésorerie Plus · Conseil en prévision de trésorerie pour TPE';
        const canonical = siteUrl ? `${siteUrl}${page.path}` : null;

        return `<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>${fullTitle}</title>
    <meta name="description" content="${description}">
    ${canonical ? `<link rel="canonical" href="${canonical}">\n    <meta property="og:url" content="${canonical}">` : ''}
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:title" content="${title ?? 'Trésorerie Plus'}">
    <meta property="og:description" content="${description}">
    <meta name="theme-color" content="#0b2545">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon.ico" sizes="any">`;
    },

    header(active = null) {
        const desktopLinks = navigation.map(([key, label]) => {
            const isActive = key === active;
            const state = isActive ? 'bg-brand-50 text-brand-700' : 'text-ink-soft hover:text-ink';

            return `<a href="${routes[key]}" class="rounded-full px-4 py-2 text-sm font-semibold transition ${state}"${isActive ? ' aria-current="page"' : ''}>${label}</a>`;
        }).join('\n                ');

        const mobileLinks = navigation.map(([key, label]) =>
            `<a href="${routes[key]}" class="rounded-xl px-4 py-3 font-semibold text-ink hover:bg-paper"${key === active ? ' aria-current="page"' : ''}>${label}</a>`,
        ).join('\n                ');

        return `<a href="#contenu" class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2">Aller au contenu</a>

    <header class="sticky top-0 z-40 border-b border-line/70 bg-white/90 backdrop-blur">
        <div class="container-page flex h-16 items-center justify-between gap-4 lg:h-20">
            <a href="/" class="shrink-0" aria-label="Trésorerie Plus, accueil">${logo()}</a>

            <nav class="hidden items-center gap-1 lg:flex" aria-label="Navigation principale">
                ${desktopLinks}
            </nav>

            <div class="flex items-center gap-2">
                <a href="tel:${advisor.phoneLink}" class="hidden items-center gap-2 px-3 text-sm font-semibold text-ink-soft hover:text-ink xl:inline-flex">
                    ${icon('phone', 'size-4')}
                    ${advisor.phone}
                </a>
                <a href="${routes.booking}" class="btn btn-primary hidden sm:inline-flex">Prendre rendez-vous</a>
                <button type="button" class="inline-flex size-11 items-center justify-center rounded-full text-ink lg:hidden" aria-expanded="false" aria-controls="menu-mobile" data-menu-toggle>
                    <span class="sr-only">Ouvrir le menu</span>
                    ${icon('menu', 'size-6')}
                </button>
            </div>
        </div>

        <nav id="menu-mobile" class="hidden border-t border-line bg-white lg:hidden" aria-label="Navigation mobile" data-menu-panel>
            <div class="container-page flex flex-col gap-1 py-4">
                ${mobileLinks}
                <a href="${routes.booking}" class="btn btn-primary mt-2">Prendre rendez-vous</a>
                <a href="tel:${advisor.phoneLink}" class="btn btn-secondary">
                    ${icon('phone', 'size-4')}
                    ${advisor.phone}
                </a>
            </div>
        </nav>
    </header>`;
    },

    footer() {
        return `<footer class="mt-24 bg-ink text-white/80">
        <div class="container-page grid gap-10 py-14 md:grid-cols-[1.4fr_1fr_1fr]">
            <div>
                ${logo(true)}
                <p class="mt-4 max-w-sm text-sm leading-relaxed">
                    Premier réseau national de conseillers spécialisés dans l'accompagnement en prévision de trésorerie des TPE.
                </p>
            </div>

            <div>
                <p class="text-sm font-bold text-white">Votre conseillère</p>
                <address class="mt-3 space-y-1 text-sm not-italic">
                    <p>${advisor.name}</p>
                    <p>${advisor.street}<br>${advisor.city}</p>
                    <p><a class="hover:text-white" href="tel:${advisor.phoneLink}">${advisor.phone}</a></p>
                    <p><a class="hover:text-white" href="mailto:${advisor.email}">${advisor.email}</a></p>
                </address>
            </div>

            <div>
                <p class="text-sm font-bold text-white">Liens utiles</p>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a class="hover:text-white" href="${routes.booking}">Prendre rendez-vous</a></li>
                    <li><a class="hover:text-white" href="${forecastToolUrl}" rel="noopener">Tester gratuitement ma trésorerie</a></li>
                    <li><a class="hover:text-white" href="${routes.network}">Rejoindre le réseau</a></li>
                    <li><a class="hover:text-white" href="${routes.legal}">Mentions légales</a></li>
                    <li><a class="hover:text-white" href="${routes.privacy}">Politique de confidentialité</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="container-page py-5 text-xs text-white/50">
                © ${new Date().getFullYear()} Trésorerie Plus · ${legal.company}
            </div>
        </div>
    </footer>`;
    },

    pageHeader(eyebrow, title, intro = '') {
        return `<section class="border-b border-line bg-white">
        <div class="container-page py-14 sm:py-20">
            ${eyebrow ? `<p class="eyebrow">${eyebrow}</p>` : ''}
            <h1 class="mt-3 max-w-3xl text-4xl font-extrabold tracking-tight text-balance sm:text-5xl">${title}</h1>
            ${intro ? `<div class="mt-5 max-w-2xl text-lg leading-relaxed text-ink-soft">${intro}</div>` : ''}
        </div>
    </section>`;
    },

    // Bandeau « formulaire désactivé » affiché au-dessus des formulaires.
    unavailable(what) {
        return `<div role="status" class="mb-6 flex items-start gap-3 rounded-2xl border border-amber-300 bg-amber-50 px-5 py-4 text-sm font-medium text-amber-800">
                ${icon('alert', 'mt-0.5 size-4 shrink-0')}
                <div>
                    ${what} est momentanément indisponible.
                    Appelez le <a class="underline" href="tel:${advisor.phoneLink}">${advisor.phone}</a>
                    ou écrivez à <a class="break-all underline" href="mailto:${advisor.email}">${advisor.email}</a>.
                </div>
            </div>`;
    },

    ctaBand() {
        return `<section class="container-page">
        <div class="relative overflow-hidden rounded-3xl bg-ink px-6 py-12 text-white sm:px-12 sm:py-16">
            <div class="pointer-events-none absolute -top-24 -right-24 size-72 rounded-full bg-brand-500/30 blur-3xl"></div>
            <div class="relative grid gap-8 lg:grid-cols-[1.4fr_1fr] lg:items-center">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Ne restez plus seul face à vos problèmes de trésorerie.</h2>
                    <p class="mt-4 max-w-xl text-white/75">Le premier rendez-vous est gratuit et sans engagement. Appelez-nous pour convenir d'un créneau qui vous convient.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col lg:items-end">
                    <a href="tel:${advisor.phoneLink}" class="btn bg-white px-6 py-3.5 text-base text-ink hover:bg-brand-50">
                        ${icon('phone', 'size-5')}
                        ${advisor.phone}
                    </a>
                    <a href="mailto:${advisor.email}" class="btn border border-white/25 px-6 py-3.5 text-base text-white hover:bg-white/10">
                        ${icon('mail', 'size-5')}
                        Nous écrire
                    </a>
                </div>
            </div>
        </div>
    </section>`;
    },
};

// Remplace les appels {{ fonction(args) }} d'une page HTML.
export function renderPartials(html, path) {
    page.path = path;

    return html.replace(/\{\{\s*(\w+)\((.*?)\)\s*\}\}/gs, (match, name, args) => {
        if (!helpers[name]) {
            throw new Error(`Partial inconnu dans ${path} : ${name}`);
        }

        return helpers[name](...JSON.parse(`[${args}]`));
    });
}
