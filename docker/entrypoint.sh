#!/bin/sh
# Runs on every container start: make sure the SQLite file exists and is
# writable, apply migrations, then warm Laravel's caches with the runtime env.
set -e

cd /var/www/html

DB_FILE="${DB_DATABASE:-/data/database.sqlite}"
mkdir -p "$(dirname "$DB_FILE")" storage/logs storage/framework/cache/data storage/framework/sessions storage/framework/views storage/app
[ -f "$DB_FILE" ] || touch "$DB_FILE"
chown -R www-data:www-data "$(dirname "$DB_FILE")" storage bootstrap/cache

su -s /bin/sh www-data -c "php artisan migrate --force --no-interaction"
su -s /bin/sh www-data -c "php artisan optimize"

exec "$@"
