FROM php:8.5-fpm-alpine

RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpq-dev \
    rabbitmq-c-dev \
    autoconf \
    g++ \
    make \
    linux-headers

RUN docker-php-ext-install pdo pdo_pgsql

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install amqp \
    && docker-php-ext-enable amqp

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["php-fpm"]
