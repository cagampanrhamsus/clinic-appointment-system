FROM php:8.4-apache

# Install system dependencies + PostgreSQL libs + Node.js
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
    gnupg

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install Node dependencies and build Vite assets
RUN npm install
RUN npm run build

# Laravel permissions
RUN chmod -R 775 storage bootstrap/cache

# Clear Laravel caches
RUN php artisan optimize:clear || true

# Railway port
EXPOSE 8080

# Start Laravel
CMD php -S 0.0.0.0:${PORT:-8080} -t public