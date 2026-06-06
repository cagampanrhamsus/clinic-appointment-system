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
    gnupg \
    nodejs \
    npm

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
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install frontend dependencies + build
RUN npm install
RUN npm run build

# Ensure Laravel required directories exist (IMPORTANT FIX)
RUN mkdir -p storage/logs bootstrap/cache

# Permissions (Railway-safe)
RUN chmod -R 777 storage bootstrap/cache

# Clear + cache config safely
RUN php artisan optimize:clear || true
RUN php artisan config:cache || true

# Enable Apache rewrite (CRITICAL for Laravel routes)
RUN a2enmod rewrite

# FIX Apache document root properly (IMPORTANT FIX)
ENV APACHE_DOCUMENT_ROOT=/var/www/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Ensure Apache listens correctly on Railway
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]