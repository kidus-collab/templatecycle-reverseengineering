# 1. We are now using Alpine Linux!
FROM php:8.4-cli-alpine

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
