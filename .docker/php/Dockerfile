ARG PHP_IMAGE_TAG

FROM php:$PHP_IMAGE_TAG

ARG WORDPRESS_DB_PASSWORD
ARG WORDPRESS_VERSION
ENV WORDPRESS_DB_PASSWORD=$WORDPRESS_DB_PASSWORD

RUN echo "http://dl-cdn.alpinelinux.org/alpine/v3.7/main" >> /etc/apk/repositories\
    && apk add --update --no-cache subversion mysql mysql-client git bash g++ make autoconf nano

RUN set -ex \
    && apk update \
    && apk add --no-cache nodejs npm git \
    && apk add --no-cache git mysql-client curl openssh-client icu libpng freetype libzip \
       libjpeg-turbo postgresql-dev libffi-dev libsodium \
    && apk add --no-cache --virtual build-dependencies icu-dev libxml2-dev freetype-dev libzip-dev libpng-dev \
        libjpeg-turbo-dev g++ make autoconf libsodium-dev\
    && apk add zlib-dev  \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mysqli pdo pdo_mysql pcntl gd \
    && apk add --no-cache libpng libpng-dev

RUN mkdir /src && cd /src && git clone https://github.com/xdebug/xdebug.git \
    && cd xdebug \
    && sh ./rebuild.sh \
    && docker-php-source extract \
    && docker-php-ext-enable xdebug mysqli pdo_mysql\
    && docker-php-source delete \
    && cd  / && rm -fr /src \
    && apk del build-dependencies \
    && rm -rf /tmp/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar  \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/bin/wp \
    && curl -L https://github.com/vishnubob/wait-for-it/raw/master/wait-for-it.sh > /tmp/wait-for-it.sh \
    && chmod +x /tmp/wait-for-it.sh

WORKDIR /tmp

COPY ./bin/install-wp-tests.sh /tmp/install-wp-tests.sh

RUN chmod +x /tmp/install-wp-tests.sh && /tmp/install-wp-tests.sh wordpress_test root $WORDPRESS_DB_PASSWORD mysql $WORDPRESS_VERSION

COPY ./db-error.php /tmp/wordpress/wp-content/db-error.php

WORKDIR /wordpress

COPY composer.json /wordpress

CMD /tmp/wait-for-it.sh mysql:3306 -- bin/install-db.sh wordpress_test root $WORDPRESS_DB_PASSWORD mysql