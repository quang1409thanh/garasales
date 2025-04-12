FROM php:8.2-fpm

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    libzip-dev \
    unzip \
    libonig-dev \
    wget \
    curl \
    nginx \
    netcat-openbsd \
    libicu-dev && \
    curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd && \
    docker-php-ext-install exif && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*


# Thêm Cloud SQL Auth Proxy
ADD https://dl.google.com/cloudsql/cloud_sql_proxy.linux.amd64 /cloud_sql_proxy
RUN chmod +x /cloud_sql_proxy

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip intl  # Cài đặt PHP intl extension
# Create necessary directories
RUN mkdir -p /run/nginx /app

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/

# Copy application files (still set ownership to www-data for PHP-FPM compatibility)
COPY --chown=www-data:www-data . /app

# Install Composer
RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && /usr/local/bin/composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist --verbose

# Install npm dependencies
RUN cd /app && npm install

# Build assets
RUN cd /app && npm run build

# Set permissions for storage and cache directories
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Set working directory
WORKDIR /app

# Expose port 80
EXPOSE 80

# Update Nginx configuration to listen on port 8080
RUN sed -i 's,LISTEN_PORT,8080,g' /etc/nginx/nginx.conf

# Copy and set permissions for the start script
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh
# test
# Set the entrypoint to the start script (container will run as root by default)
ENTRYPOINT ["/start.sh"]
