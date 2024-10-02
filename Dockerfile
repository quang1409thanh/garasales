FROM php:8.2-fpm

# Install dependencies
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
    netcat-openbsd

# Add Cloud SQL Auth Proxy
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
RUN wget http://getcomposer.org/composer.phar && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer
RUN cd /app && composer install --no-interaction --prefer-dist --optimize-autoloader

# Change ownership of /app to www-data
RUN chown -R www-data: /app

# Set working directory
WORKDIR /app

# Expose port 80
EXPOSE 80

# Update nginx config
RUN sed -i 's,LISTEN_PORT,8080,g' /etc/nginx/nginx.conf

# Run Cloud SQL Auth Proxy and application
ENTRYPOINT ["/bin/bash", "-c", "/cloud_sql_proxy -dir=/cloudsql -instances=garasalecss:asia-east2:garasale=tcp:3306 & \
                              php-fpm -D && \
                              while ! nc -w 1 -z 127.0.0.1 9000; do sleep 0.1; done && \
                              nginx -g 'daemon off;'"]
