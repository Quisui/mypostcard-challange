#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}

if [ "$role" = "app" ]; then
    echo "Running the app..."
    exec apache2-foreground

elif [ "$role" = "queue" ]; then

    echo "Running the queue..."
    php /var/www/artisan queue:listen --verbose --tries=1 --timeout=90

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
