FROM node:18 AS build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY webpack.config.js tailwind.config.js postcss.config.js ./
COPY themes/opatrimoine ./themes/opatrimoine
RUN npm run build

# From official WordPress image with Apache + PHP
FROM wordpress:php8.2-apache

# Install Composer
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev less mariadb-client \
    && docker-php-ext-install zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

WORKDIR /var/www/html

# Copy composer files only (better caching)
COPY composer.json composer.lock ./

# Install WordPress + plugins via Composer
RUN composer install --no-dev --prefer-dist --no-interaction

COPY --from=build /app/themes/opatrimoine ./wp/wp-content/themes/opatrimoine/

# Copy everything except themes (since you mount them)
COPY wp-config.php .
COPY index.php .
COPY favicon.ico .

RUN mv plugins/* wp/wp-content/plugins/
RUN rmdir plugins

COPY mu-plugins wp/wp-content/mu-plugins


RUN rmdir wp-content/cache wp-content/plugins wp-content/themes wp-content

RUN mkdir -p wp/wp-content/uploads \
    && chown -R www-data:www-data wp/

RUN echo "LimitRequestBody 536870912" > /etc/apache2/conf-enabled/upload.conf
