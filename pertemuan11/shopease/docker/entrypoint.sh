#!/bin/sh
set -e
cd /var/www/html

mkdir -p writable/uploads/products writable/uploads/proofs writable/session writable/cache writable/logs
chmod -R u+rwX,g+rwX writable || true

if [ "${RUN_MIGRATIONS:-1}" = "1" ]; then
    attempts=0
    until php spark migrate --all; do
        attempts=$((attempts + 1))
        if [ "$attempts" -ge 30 ]; then
            echo "Database not reachable after 30 attempts" >&2
            exit 1
        fi
        echo "Waiting for database ($attempts/30)..."
        sleep 2
    done
fi

if [ "${RUN_SEEDER:-1}" = "1" ]; then
    php spark db:seed DatabaseSeeder
fi

exec "$@"
