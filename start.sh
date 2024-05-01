##!/usr/bin/env bash
#echo "Running composer"
#composer global require hirak/prestissimo
#composer install --no-dev --working-dir=/var/www/html
#
#echo "Caching config..."
#php artisan config:cache
#
#echo "Caching routes..."
#php artisan route:cache
#
#echo "Running migrations..."
#php artisan migrate --force

#/////////////////////////

#echo "Starting PHP built-in server..."
#php -S 0.0.0.0:8000 -t public

#////////////////////////

#!/usr/bin/env bash
#echo "Running composer"
#composer install --no-dev --working-dir=/var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force
