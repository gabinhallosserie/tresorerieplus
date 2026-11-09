# Trésorerie Plus – site vitrine (front statique)

Site statique de tresorerie-plus.fr, construit avec Vite et Tailwind CSS 4, déployé sur Netlify.

Les formulaires de contact et de prise de rendez-vous sont **désactivés** : la branche `main` ne contient aucun back-end. Les visiteurs sont invités à appeler ou à écrire.

L'application Laravel complète (prise de rendez-vous, back-office, e-mails, déploiement Docker) est conservée sur la branche **`backend`**.

## Développement

```bash
npm install
npm run dev       # http://localhost:5173
npm run build     # génère dist/
npm run preview   # sert dist/ en local
```

## Structure

| Chemin | Rôle |
| --- | --- |
| `index.html`, `<page>/index.html` | Une page par dossier (URL `/methode/`, `/contact/`…) |
| `404.html` | Page d'erreur servie par Netlify |
| `src/site.js` | Coordonnées, mentions légales, routes, et fragments partagés (en-tête, pied de page, icônes…) |
| `src/css/app.css` | Thème Tailwind et composants (`.btn`, `.card`…) |
| `src/js/app.js` | Polices et menu mobile |
| `public/` | Fichiers copiés tels quels (images, favicon, robots.txt) |

Dans les pages, `{{ fonction("arg") }}` appelle une fonction de `helpers` dans `src/site.js` au moment du build, par exemple `{{ header("method") }}`, `{{ icon("phone", "size-4") }}` ou `{{ advisor("phone") }}`.

Pour ajouter une page, créez `nom-de-page/index.html` en reprenant la structure d'une page existante : Vite la détecte automatiquement.

## Déploiement Netlify

La configuration est dans `netlify.toml` (`npm run build`, dossier publié `dist`). Netlify fournit l'URL du site au build (variable `URL`) pour les balises canoniques. Pour un domaine personnalisé, vous pouvez la forcer avec `SITE_URL`.
