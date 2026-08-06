# 1. We are now using Alpine Linux!
FROM php:8.4-cli-alpine

# 2. Alpine uses 'apk' instead of 'apt-get'
# We also install nodejs and npm here so Vite has access to PHP!
RUN apk update && apk add --no-cache \
    sqlite-dev \
    libzip-dev \
    zlib-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app
