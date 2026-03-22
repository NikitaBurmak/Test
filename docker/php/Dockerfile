FROM php:8.5-fpm-alpine

RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpq-dev \
    rabbitmq-c-dev \
    autoconf \
    g++ \
    make

RUN docker-php-ext-install pdo pdo_pgsql

RUN pecl install amqp \
    && docker-php-ext-enable amqp

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["php-fpm"]
