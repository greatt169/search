FROM php:7.1-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        wget \
        zip \
        unzip \
        ssh-client \
        zlib1g-dev \
        libxml2-dev \
        libpq-dev \
        libssh-dev \
        libmcrypt-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
    && docker-php-ext-install \
        iconv \
        mbstring \
        mysqli \
        pdo_mysql \
        zip \
        soap

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN usermod -u 1000 www-data
RUN mkdir -pv /var/www/public/bitrix/tmp \
    && chown -R www-data:www-data /var/www/public/bitrix/tmp \
    && mkdir -pv /var/www/public/upload \
    && chown -R www-data:www-data /var/www/public/upload \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

ADD ./php-bitrix.ini /usr/local/etc/php/conf.d/php-bitrix.ini

WORKDIR "/var/www"