#!/bin/bash

# Fix permissions for laravel.log to ensure PHP-FPM (running as www-data) can write to it
if [ -f /app/storage/logs/laravel.log ]; then
    echo "Fixing permissions for laravel.log..."
    chown www-data:www-data /app/storage/logs/laravel.log
    chmod 664 /app/storage/logs/laravel.log
else
    echo "Creating laravel.log..."
    touch /app/storage/logs/laravel.log
    chown www-data:www-data /app/storage/logs/laravel.log
    chmod 664 /app/storage/logs/laravel.log
fi

# Start Cloud SQL Auth Proxy in the background
/cloud_sql_proxy -dir=/cloudsql -instances=garasalevnu:asia-east2:dbgarasale=tcp:3306 &

### Run migrate:fresh and seed to reset the database and populate it with initial data
#echo "Running migrate:fresh and seeding..."
#php artisan migrate --force
#php artisan migrate:fresh --seed --force

# Start PHP-FPM in the background
php-fpm -D

# Wait for PHP-FPM to be ready on port 9000
while ! nc -w 1 -z 127.0.0.1 9000; do sleep 0.1; done

# Run Laravel storage:link command
php artisan storage:link

# Debug information: Show user, permissions, and processes
echo -e "\n==============================="
echo -e "🚀 Laravel container started"
echo -e "👤 Current user: $(whoami)"
echo -e "🧾 UID: $(id -u), GID: $(id -g)"
echo -e "🔧 PHP-FPM processes:"
ps aux | grep php-fpm | grep -v grep
echo -e "\n📁 /app ownership and permissions:"
ls -ld /app
echo -e "\n📁 /app/storage:"
ls -la /app/storage
echo -e "\n📁 /app/bootstrap/cache:"
ls -la /app/bootstrap/cache
echo -e "\n📜 /app/storage/logs/laravel.log permissions:"
ls -l /app/storage/logs/laravel.log
echo -e "===============================\n"

# Start Nginx in the foreground
nginx -g 'daemon off;'
