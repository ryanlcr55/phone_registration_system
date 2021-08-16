#!/bin/bash
#cp .env.example .env
docker-compose exec app composer install

docker-compose exec app  php artisan key:generate
docker-compose exec app chown -R 1000:1000 storage bootstrap/cache
docker-compose exec app  chmod 755 -R storage bootstrap/cache
docker-compose exec app  php artisan migrate --seed

