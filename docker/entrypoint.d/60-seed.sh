#!/bin/sh
# Exécuté après les migrations automatiques (50-laravel-automations).
# Le seeder est idempotent : il crée/met à jour le compte admin et les
# réglages par défaut sans dupliquer les données existantes.
# Pratique sur l'offre gratuite de Render, qui n'a pas de shell.

cd /var/www/html || exit 0

if [ -n "$ADMIN_PASSWORD" ]; then
    php artisan db:seed --force --no-interaction || echo "⚠️  Seeder en échec, démarrage poursuivi."
else
    echo "ℹ️  ADMIN_PASSWORD non défini : seeder ignoré."
fi
