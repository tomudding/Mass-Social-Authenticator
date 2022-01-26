FROM php:8.1-fpm

RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        libcurl4-openssl-dev \
        libicu-dev \
        libzip-dev \
        nano \
        nodejs \
        unzip \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
        pcntl \
        pdo_mysql \
        zip

WORKDIR /var/www

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');"

COPY --chown=www-data:www-data ./src/composer.json ./src/composer.lock ./
RUN php composer.phar install \
    && chown -R www-data:www-data /var/www/vendor

COPY --chown=www-data:www-data ./src /var/www

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
