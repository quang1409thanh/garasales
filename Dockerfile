FROM php:8.2-fpm

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    libzip-dev \
    unzip \
    git \
    libonig-dev \
    wget \
    curl \
    nginx \
    netcat-openbsd  # Cài đặt netcat từ gói netcat-openbsd

# Thêm Cloud SQL Auth Proxy
ADD https://dl.google.com/cloudsql/cloud_sql_proxy.linux.amd64 /cloud_sql_proxy
RUN chmod +x /cloud_sql_proxy


# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Create necessary directories
RUN mkdir -p /run/nginx /app

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Copy application files
COPY . /app

# Install Composer
RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && /usr/local/bin/composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist

# Change ownership of /app to www-data
RUN chown -R www-data: /app
# Chuyển đến thư mục /app
WORKDIR /app


# Expose port 80
EXPOSE 80
# Cập nhật cấu hình Nginx
RUN sed -i 's,LISTEN_PORT,8080,g' /etc/nginx/nginx.conf

# Khởi chạy Cloud SQL Auth Proxy và ứng dụng
ENTRYPOINT ["/bin/bash", "-c", "/cloud_sql_proxy -dir=/cloudsql -instances=auction-430716:asia-east2:auction=tcp:3306 & \
                              php-fpm -D && \
                              while ! nc -w 1 -z 127.0.0.1 9000; do sleep 0.1; done && \
                              nginx -g 'daemon off;'"]
