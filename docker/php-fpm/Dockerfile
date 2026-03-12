FROM php:8.4-fpm-alpine

# Install packages
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash icu-dev imagemagick-dev libpng-dev zip libzip-dev\
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-enable intl

#zip
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip

# Postgres
RUN apk add --no-cache mysql-client && docker-php-ext-install pdo_mysql

# Imagick
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS imagemagick-dev \
&& pecl install imagick \
&& docker-php-ext-enable imagick \
&& apk del .build-deps

#GD
RUN docker-php-ext-install gd

# Configure non-root user.
ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

USER www-data

# Source code
COPY ./ /var/www
WORKDIR /var/www

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#RUN composer install --no-interaction

CMD php-fpm

EXPOSE 9000
