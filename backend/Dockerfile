FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
        libpq-dev \
        git \
        unzip \
    && pecl install phalcon \
    && docker-php-ext-enable phalcon \
    && docker-php-ext-install pdo_pgsql \
    && apt-get clean

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json .
RUN composer install --no-interaction --prefer-dist

COPY . .

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
