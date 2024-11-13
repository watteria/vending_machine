FROM php:8.2-fpm AS php_base

ARG UID
EXPOSE $UID

RUN adduser -u ${UID} --disabled-password --gecos "" appuser
RUN mkdir /home/appuser/.ssh
RUN chown -R appuser:appuser /home/appuser/
RUN echo "StrictHostKeyChecking no" >> /home/appuser/.ssh/config
RUN echo "export COLUMNS=300" >> /home/appuser/.bashrc

COPY ./php.ini /usr/local/etc/php/php.ini

RUN apt-get update \
    && apt-get install -y supervisor git acl openssl openssh-client wget zip vim librabbitmq-dev libssh-dev \
    && apt-get install -y libpng-dev zlib1g-dev libzip-dev libxml2-dev libicu-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip gd soap bcmath sockets \
    && pecl install xdebug amqp mongodb \
    && docker-php-ext-enable --ini-name 05-opcache.ini opcache xdebug amqp mongodb

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer
RUN composer self-update

RUN mkdir -p /appdata/www && chown -R appuser:appuser /appdata/www
RUN mkdir -p /appdata/www/var/cache/
RUN chmod -R 777 /appdata/www/var/cache/

USER appuser
RUN git config --global --add safe.directory /appdata/www

WORKDIR /appdata/www
