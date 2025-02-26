#!/bin/bash


chmod -R 777 storage/logs

if [ ! -d "vendor" ]; then
    echo "Vendor directory not found. Running composer install..."
    composer install --no-ansi --no-interaction --no-progress --prefer-dist --optimize-autoloader
fi

cp .env.example .env
php artisan key:generate
chmod 777 .env

echo "Waiting for database..."
until mysqladmin ping -h db -uroot -proot --silent; do
  echo "Waiting... "
  sleep 2
done

php artisan migrate --force

if [ ! -d "node_modules" ]; then
    echo "node_modules directory not found. Running npm install..."
    npm install --omit=dev
fi

supervisord
php artisan opti:clear
php artisan webpush:vapid

cron

./vendor/bin/pest --coverage --colors=never > list_tests_results

echo "" > storage/logs/laravel.log

exec "$@"
