#!/bin/bash

# Start Cloud SQL Auth Proxy
/cloud_sql_proxy -dir=/cloudsql -instances=garasalevnu:asia-east2:dbgarasale=tcp:3306 &

# Start PHP-FPM
php-fpm -D

# Wait for PHP-FPM to be ready
while ! nc -w 1 -z 127.0.0.1 9000; do sleep 0.1; done

# Laravel setup command
php artisan storage:link

# === Debug Info: Permissions and Runtime Users ===
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
echo -e "===============================\n"

# Start Nginx in foreground
nginx -g 'daemon off;'
