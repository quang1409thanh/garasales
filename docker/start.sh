#!/bin/bash

# Khởi động Cloud SQL Auth Proxy
/cloud_sql_proxy -dir=/cloudsql -instances=garasalecss:asia-east2:garasale=tcp:3306 &

# Khởi động PHP-FPM
php-fpm -D

# Chờ cho PHP-FPM khởi động
while ! nc -w 1 -z 127.0.0.1 9000; do sleep 0.1; done

# Chạy lệnh Artisan
php artisan migrate:fresh --seed
php artisan storage:link

# Khởi động Nginx
nginx -g 'daemon off;'
