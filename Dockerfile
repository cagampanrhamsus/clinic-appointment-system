FROM php:8.4-apache

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node 22 is required by Vite 8 / laravel-vite-plugin 3.
COPY --from=node:22-bookworm-slim /usr/local/bin/node /usr/local/bin/node
COPY --from=node:22-bookworm-slim /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
 && ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

WORKDIR /var/www

# Copy project
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install frontend dependencies + build
RUN npm ci
RUN npm run build

# Ensure Laravel required directories exist
RUN mkdir -p \
    storage/logs \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rwX storage bootstrap/cache

# Enable Apache modules for Laravel. mod_php uses prefork; make that explicit.
RUN a2dismod mpm_event mpm_worker || true \
 && a2enmod mpm_prefork rewrite

# FIX Apache document root properly (IMPORTANT FIX)
ENV APACHE_DOCUMENT_ROOT=/var/www/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Railway provides the actual runtime port in $PORT.
EXPOSE 8080

# Start Laravel directly on Railway's runtime port.
CMD php artisan optimize:clear --no-ansi || true; \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
