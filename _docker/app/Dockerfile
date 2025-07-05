FROM composer:latest AS composer

FROM php:8.4-fpm-alpine3.21

ARG TZ
ARG UID
ARG GID

# Установка timezone
RUN set -eux \
    && apk add --no-cache tzdata \
    && cp /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo "${TZ}" > /etc/timezone \
    && apk del tzdata

# Установка пакетов
RUN set -eux \
    && apk add --no-cache \
        shadow \
        openssh \
        openssl \
        bash \
        linux-headers \
        freetype \
        icu \
        icu-libs \
        libzip \
        libpng \
        libjpeg-turbo \
        imagemagick \
        build-base \
        procps \
        make \
        wget \
        libwebp \
        libwebp-tools \
        lz4 \
        mysql-client \
        supervisor

# Установка php-пакетов
RUN set -eux \
    && apk add --no-cache --virtual .build-deps-php \
        $PHPIZE_DEPS \
        openssl-dev \
        zlib-dev \
        imagemagick-dev \
        icu-dev \
        g++ \
        automake \
        autoconf \
        libzip-dev \
        libpng-dev \
        libwebp-dev \
        libjpeg-turbo-dev \
        freetype-dev \
    && pecl install \
        redis \
        xdebug \
    && docker-php-ext-configure \
        gd --enable-gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        gd \
        mysqli \
        pdo \
        pdo_mysql \
        intl \
        pcntl \
        opcache \
        exif \
        zip \
    && docker-php-ext-enable \
        redis \
        xdebug \
    && apk del -f .build-deps-php \
    && rm -rf /usr/src/php* /tmp/pear

# Копируем php.ini
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Копируем composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Копируем конфиг supervisord
COPY supervisord.conf /etc/supervisord.conf

# Настройка пользователя
RUN usermod -u ${UID} www-data && groupmod -g ${GID} www-data
USER www-data

# Порты
EXPOSE 9000

# Запуск supervisord
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
