#!/bin/sh
# Boot: prepare SQLite, run migrations + idempotent seed, then serve.
set -e

DB_FILE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
mkdir -p "$(dirname "$DB_FILE")"
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

if [ ! -f "$DB_FILE" ]; then
    touch "$DB_FILE"
fi

# Laravel reads configuration from environment variables on Render;
# make sure no stale cached config survives from the image build.
php artisan config:clear >/dev/null 2>&1 || true

php artisan migrate --force
# Seeder is idempotent (firstOrCreate), so it is safe on every boot;
# this also re-populates the demo data if the free-tier disk was reset.
php artisan db:seed --force

chown -R www-data:www-data storage bootstrap/cache "$(dirname "$DB_FILE")"

exec apache2-foreground
