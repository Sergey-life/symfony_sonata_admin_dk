FROM php:8.1.0-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash

RUN apk add --no-cache \
      libzip-dev \
      zip \
    && docker-php-ext-install zip

RUN #pecl install xdebug && docker-php-ext-enable xdebug

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./volumes/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
#Cron
#RUN apt-get -y install cron
#COPY crontab /etc/crontabs/root
#RUN mkdir /var/log/cron

CMD ["crond", "-f"]

RUN chown www-data:www-data /var/www/html
COPY --chown=www-data:www-data ./ /var/www/html
WORKDIR /var/www/html/app
