# Trésorerie Plus – site et prise de rendez-vous

Refonte de tresorerie-plus.fr en Laravel 13 (PHP 8.3), Tailwind CSS 4 et Vite.

## Fonctionnalités

- Site vitrine responsive : accueil, méthode, réseau, contact, mentions légales, confidentialité.
- Formulaire de contact : enregistré en base et envoyé par e-mail, avec anti-spam (champ piège et limite d'envois).
- Prise de rendez-vous en ligne :
  - créneaux calculés à partir des horaires d'ouverture, de la durée, de la pause, du délai minimum et des fermetures ;
  - confirmation par e-mail avec fichier .ics, rappel automatique la veille, annulation par le client.
- Espace admin (`/admin`) :
  - calendrier hebdomadaire et détail des rendez-vous (note interne, annulation) ;
  - gestion des horaires, des règles et des congés ;
  - lecture des messages de contact ;
  - abonnement Google Agenda / Outlook.

## Installation locale

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

Renseignez `ADMIN_PASSWORD` dans `.env`, puis :

```bash
php artisan db:seed
npm run build
php artisan serve
```

Les e-mails sont écrits dans `storage/logs/laravel.log` (`MAIL_MAILER=log`).

Tests : `php artisan test`

## Variables d'environnement propres au projet

| Variable | Rôle |
| --- | --- |
| `ADMIN_NAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD` | Compte admin créé par `php artisan db:seed` |
| `ADVISOR_NOTIFICATION_EMAIL` | Adresse qui reçoit les nouveaux rendez-vous et messages |
| `CALENDAR_FEED_TOKEN` | Jeton secret de l'URL d'abonnement agenda (générer avec `php -r "echo bin2hex(random_bytes(20));"`) |

Coordonnées, mentions légales et témoignages : `config/site.php`.

## Mise en ligne chez OVH (hébergement mutualisé)

1. **PHP 8.3** : dans l'espace client OVH, *Hébergement > Informations générales > Version PHP* (ou fichier `.ovhconfig` avec `app.engine.version=8.3`).
2. **Base MySQL** : créez une base, puis renseignez `DB_CONNECTION=mysql`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME` et `DB_PASSWORD`.
3. **Fichiers** : envoyez le projet, `vendor/` et `public/build/` compris (lancer `composer install --no-dev --optimize-autoloader` et `npm run build` avant l'envoi). Le dossier racine du multisite doit pointer vers **`public/`**.
4. **`.env` de production** : `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://www.tresorerie-plus.fr`, `APP_KEY` généré.
5. **Base** : `php artisan migrate --force` puis `php artisan db:seed --force` (en SSH).
6. **Optimisations** : `php artisan config:cache && php artisan route:cache && php artisan view:cache`.

### HTTPS

Le certificat actuel ne correspond pas au domaine : les navigateurs refusent `https://tresorerie-plus.fr`.

1. Dans *Multisite*, activez **SSL** pour `tresorerie-plus.fr` **et** `www.tresorerie-plus.fr`, puis commandez/régénérez le certificat Let's Encrypt (gratuit) dans l'onglet *Informations générales*.
2. Une fois le certificat actif, décommentez le bloc « Forcer HTTPS » de `public/.htaccess`, qui redirige http vers https et sans-www vers www.
3. `APP_ENV=production` force déjà la génération de toutes les URL en https.
4. Mettez à jour le lien vers l'outil `tresorerie-plus-prog.fr`, qui doit lui aussi passer en HTTPS.

### E-mails

Configurez le SMTP d'une adresse OVH, par exemple `contact@tresorerie-plus.fr` :

```
MAIL_MAILER=smtp
MAIL_HOST=ssl0.ovh.net
MAIL_PORT=465
MAIL_SCHEME=smtps
MAIL_USERNAME=contact@tresorerie-plus.fr
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=contact@tresorerie-plus.fr
```

### Rappels automatiques

Dans *Hébergement > Tâches planifiées (Cron)*, ajoutez une tâche **toutes les heures** :

```
cd ~/www && php artisan schedule:run
```

(adapter le chemin au dossier du site). Elle envoie le rappel aux clients dont le rendez-vous a lieu dans les 24 heures.

### Redirections des anciennes URL

Les anciennes pages (`/FR/index.php`, `/FR/apropos.php`…) sont redirigées en 301 dans `public/.htaccess` pour ne pas perdre le référencement.
