#!/usr/bin/env bash
 
set -e
 
role=${CONTAINER_ROLE:-app}

env=${APP_ENV:-production}

if [ "$env" != "local" ]; then
    echo "Caching configuration..."
    (cd /var/www/html && php artisan config:cache && php artisan route:cache && php artisan view:cache)
fi

if [ "$role" = "scheduler" ]; then
 
    while [ true ]
        do
        php /var/www/html/artisan schedule:run & sleep 60
        done
else
    echo "Could not match the container role \"$role\""
    exit 1
fi