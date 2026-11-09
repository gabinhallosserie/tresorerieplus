import { readdirSync, statSync } from 'node:fs';
import { dirname, join, relative, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { renderPartials } from './src/site.js';

const root = dirname(fileURLToPath(import.meta.url));
const ignored = new Set(['node_modules', 'dist', 'public', 'src', '.git']);

// Toutes les pages : index.html à la racine et dans chaque dossier de page.
function findPages(dir, pages = {}) {
    for (const entry of readdirSync(dir)) {
        const full = join(dir, entry);

        if (statSync(full).isDirectory()) {
            if (!ignored.has(entry) && !entry.startsWith('.')) {
                findPages(full, pages);
            }
        } else if (entry.endsWith('.html')) {
            const name = relative(root, full).replace(/\\/g, '/').replace(/\.html$/, '');
            pages[name] = full;
        }
    }

    return pages;
}

const partials = {
    name: 'partials',
    transformIndexHtml: {
        order: 'pre',
        handler: (html, ctx) => renderPartials(html, ctx.path.replace(/index\.html$/, '')),
    },
};

export default defineConfig({
    plugins: [partials, tailwindcss()],
    build: {
        rollupOptions: {
            input: findPages(resolve(root)),
        },
    },
});
