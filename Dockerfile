FROM php:8.3-apache

# Install required packages and PHP extensions
RUN apt-get update && apt-get install -y zip unzip libsqlite3-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Change document root to Laravel's /public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
