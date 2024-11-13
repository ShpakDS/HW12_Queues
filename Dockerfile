# Dockerfile.php
FROM php:8.0-cli

RUN apt-get update && \
    apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev unzip && \
    if ! php -m | grep -q 'redis'; then pecl install redis && docker-php-ext-enable redis; fi

# Встановлення Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer