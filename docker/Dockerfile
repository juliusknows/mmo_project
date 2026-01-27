#------- BASE -------
FROM php:8.3-fpm AS base

RUN apt-get update && apt-get install -y --no-install-recommends \
    ssh \
    git \
    sudo \
    cron \
    locales \
    libzip-dev \
    libgmp-dev \
    libicu-dev \
    libpq-dev \
    unzip \
    nano \
    iputils-ping \
    htop \
    librabbitmq-dev \
    supervisor

RUN docker-php-ext-install zip \
    pdo \
    pdo_mysql \
    intl opcache sockets \
    && docker-php-ext-configure intl \
    && docker-php-ext-enable opcache

RUN sed -i -e \
  's/# ru_RU.UTF-8 UTF-8/ru_RU.UTF-8 UTF-8/' /etc/locale.gen \
   && locale-gen

ENV LANG ru_RU.UTF-8
ENV LANGUAGE ru_RU:ru
ENV LC_LANG ru_RU.UTF-8
ENV LC_ALL ru_RU.UTF-8

ENV TZ="UTC"

COPY --from=composer:2.9 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

ENV PATH="$PATH:/var/www/vendor/bin:/var/www/bin"

ENTRYPOINT ["/bin/bash", "/var/www/bin/entrypoint.sh"]
CMD ["php-fpm"]

COPY ./composer.json /var/www/composer.json
COPY ./composer.lock /var/www/composer.lock
COPY ./symfony.lock /var/www/symfony.lock
COPY ./bin/ /var/www/bin/
COPY ./public/ /var/www/public/
COPY ./config/ /var/www/config/
COPY ./.env /var/www/.env
#COPY ./migrations /var/www/migrations
COPY ./src/ /var/www/src/

RUN composer install --prefer-dist --no-scripts --no-dev --optimize-autoloader --classmap-authoritative && \
    chmod +x /var/www/bin/entrypoint.sh

#------- END BASE -------

#------- DEV -------
FROM base AS dev

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="1"
ENV APP_ENV=dev

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"  && \
    echo 'user = root' >> $PHP_INI_DIR/../php-fpm.d/www.conf  && \
    echo 'group = root' >> $PHP_INI_DIR/../php-fpm.d/www.conf && \
    composer install --prefer-dist --no-scripts --optimize-autoloader

#COPY ./.php-cs-fixer.dist.php /var/www/.php-cs-fixer.dist.php
#COPY ./phpstan.neon.dist /var/www/phpstan.neon.dist
#COPY ./psalm.xml /var/www/psalm.xml
#COPY ./phpunit.xml /var/www/phpunit.xml
COPY ./tests /var/www/tests

COPY ./docker/php/xdebug.ini $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini
COPY ./docker/php/symfony.ini $PHP_INI_DIR/conf.d/symfony.ini
COPY ./docker/php/symfony-fpm-pool.conf $PHP_INI_DIR/../php-fpm.d/www.conf

#------ END DEV--------

#------- PROD -------
FROM base AS prod

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"
ENV PHP_OPCACHE_PRELOAD="/var/www/config/preload.php"
ENV APP_ENV=prod

RUN mkdir -p /var/run/supervisor && \
    chown www-data:www-data /var/run/supervisor

RUN mkdir -p /var/www/var/cache/prod && \
    chown -R www-data:www-data /var/www && \
    echo "www-data   ALL=NOPASSWD: /usr/sbin/cron" >> /etc/sudoers  && \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    apt-get -y clean > /dev/null && \
    echo 'user = www-data' >> $PHP_INI_DIR/../php-fpm.d/www.conf && \
    echo 'group = www-data' >> $PHP_INI_DIR/../php-fpm.d/www.conf

USER www-data:www-data

#------ END PROD--------
