# Start from official WordPress image with Apache + PHP
# FROM node:18 AS build
# WORKDIR /app
# COPY ./public/themes/opatrimoine ./opatrimoine
# RUN cd opatrimoine && npm install && npm run build

FROM wordpress:php8.2-apache

# Install Composer
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy composer files only (better caching)
COPY composer.json composer.lock ./

# Install WordPress + plugins via Composer
RUN composer install --no-dev --prefer-dist --no-interaction

# Copy everything except themes (since you mount them)
COPY public/wp-config.php .
COPY public/index.php .
COPY public/favicon.ico .
# COPY --from=build /app/opatrimoine/assets ./wp-content/themes/opatrimoine/assets

# Optionally exclude themes if you don’t want them baked in:
# COPY public ./public
# RUN rm -rf ./public/wp-content/themes/*

# Apache config tweaks (optional)
# RUN a2enmod rewrite