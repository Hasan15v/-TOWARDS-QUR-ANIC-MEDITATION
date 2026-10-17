
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json ./
RUN npm install

COPY . .
RUN npm run build


FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath exif opcache intl zip \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri \
    -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    && sed -ri \
    -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .

COPY --from=frontend /app/public/build ./public/build

RUN composer dump-autoload --no-dev --optimize \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
