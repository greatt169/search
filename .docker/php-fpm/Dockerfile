FROM php:7.1-fpm

# Hack for debian-slim to make the jdk install work below.
RUN mkdir -p /usr/share/man/man1

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
        default-jdk-headless \
        supervisor \
    && docker-php-ext-install \
        pcntl \
        iconv \
        mbstring \
        mysqli \
        pdo_mysql \
        zip \
        soap

COPY ./docker/php-fpm/supervisord.conf /etc/supervisor
COPY ./docker/php-fpm/supervisorhorizon.conf /etc/supervisor/conf.d

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN usermod -u 1000 www-data

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

ADD ./php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR "/var/www"