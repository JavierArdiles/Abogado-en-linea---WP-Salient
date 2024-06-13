FROM php:8.1-cli-alpine

WORKDIR /var/www/html/

RUN set -ex \
    && apk update \
    && apk add --no-cache nodejs npm git \
    && apk add --no-cache git mysql-client curl openssh-client icu libpng freetype libzip \
       libjpeg-turbo postgresql-dev libffi-dev libsodium \
    && apk add --no-cache --virtual build-dependencies icu-dev libxml2-dev freetype-dev libzip-dev libpng-dev \
        libjpeg-turbo-dev g++ make autoconf libsodium-dev\
    && apk add zlib-dev  \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip \
    && apk add --no-cache libpng libpng-dev && docker-php-ext-install gd \
    && mkdir /src && cd /src && git clone https://github.com/xdebug/xdebug.git \
    && cd xdebug \
    && sh ./rebuild.sh \
    && docker-php-source extract \
    && docker-php-ext-enable xdebug\
    && docker-php-source delete \
    && cd  / && rm -fr /src \
    && apk del build-dependencies \
    && rm -rf /tmp/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
    chmod +x wp-cli.phar && \
    mv wp-cli.phar /usr/bin/wp

USER root

RUN mkdir -p ~/.ssh

RUN chmod -R 700 ~/.ssh 

RUN echo "Host * \n\tStrictHostKeyChecking no\n" >> ~/.ssh/config