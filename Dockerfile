# ---------------------------------------------------------------
# 1. Dépendances PHP (vendor/), nécessaires aussi au build Tailwind
#    qui scanne les vues de pagination du framework.
# ---------------------------------------------------------------
FROM serversideup/php:8.4-fpm-nginx AS vendor

WORKDIR /var/www/html
COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist

# ---------------------------------------------------------------
# 2. Assets front (Vite + Tailwind) -> public/build
# ---------------------------------------------------------------
FROM node:24-alpine AS assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
COPY --from=vendor /var/www/html/vendor ./vendor
RUN npm run build

# ---------------------------------------------------------------
# 3. Image finale : Nginx + PHP-FPM (écoute sur le port 8080)
# ---------------------------------------------------------------
FROM serversideup/php:8.4-fpm-nginx

# Au démarrage : storage:link, migrate --force, config/route/view cache.
ENV AUTORUN_ENABLED=true \
    PHP_OPCACHE_ENABLE=1

WORKDIR /var/www/html

COPY --chown=www-data:www-data --from=vendor /var/www/html/vendor ./vendor
COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize --no-dev --no-interaction \
    && php artisan package:discover --ansi

USER root
COPY --chmod=755 docker/entrypoint.d/ /etc/entrypoint.d/
USER www-data
