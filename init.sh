#!/bin/bash

if [ ! -f .env ]; then
    echo "coping .env.example → .env"
    cp .env.example .env
else
    echo ".env already exists"
fi

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

./vendor/bin/sail up -d

echo "waiting 10 sec to starting sail"
sleep 10

./vendor/bin/sail artisan key:generate

echo "Initialization complete"
