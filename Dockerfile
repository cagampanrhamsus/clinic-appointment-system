# Use official PHP image
FROM php:8.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy ONLY composer files first (IMPORTANT FIX)
COPY composer.json composer.lock ./

# Install dependencies (stable layer)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy full project AFTER dependencies
COPY . .

# Fix Laravel permissions
RUN chmod -R 775 storage bootstrap/cache

# Generate app key (safe fallback)
RUN php artisan key:generate

# Expose Render port
EXPOSE 10000

# Start Laravel
CMD php -S 0.0.0.0:$PORT -t public