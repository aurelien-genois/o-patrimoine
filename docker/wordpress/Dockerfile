FROM node:18 AS build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY webpack.config.js tailwind.config.js postcss.config.js ./
COPY public/themes/opatrimoine ./public/themes/opatrimoine
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

COPY --from=build /app/public/themes/opatrimoine ./wp/wp-content/themes/opatrimoine/

# Copy everything except themes (since you mount them)
COPY public/wp-config.php .
COPY public/index.php .
COPY public/favicon.ico .

RUN mv plugins/* wp/wp-content/plugins/
RUN rmdir plugins

RUN if [ -d "mu-plugins" ] && [ "$(ls -A mu-plugins)" ]; then \
      mv mu-plugins/* wp/wp-content/mu-plugins/; \
    fi

RUN rmdir wp-content/cache wp-content/plugins wp-content/themes wp-content