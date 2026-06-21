# Stage 1: Build Backend (Composer)
FROM composer:latest AS vendor
WORKDIR /app
COPY . .
RUN composer install --no-interaction --prefer-dist --ignore-platform-reqs --no-dev --optimize-autoloader

# Stage 2: Build Frontend Assets
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
# Salin folder vendor dari stage 1 agar Ziggy bisa dibaca oleh Vite
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

# Stage 3: Setup Production Server
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . .

# Copy vendor dari stage 1
COPY --from=vendor /app/vendor ./vendor

# Copy frontend build dari stage 2
COPY --from=frontend /app/public/build ./public/build

# Set proper permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
