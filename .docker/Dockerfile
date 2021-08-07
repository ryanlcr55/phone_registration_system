FROM php:8-fpm-buster

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN  sed -i 's/deb.debian.org/opensource.nchc.org.tw/g'  /etc/apt/sources.list

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libmemcached-dev \
        libz-dev \
        libpq-dev \
        libssl-dev \
        libmcrypt-dev \
        openssh-server \
        libxml2-dev \
        libzip-dev \
        unzip \
        && rm -r /var/lib/apt/lists/*

RUN docker-php-ext-install soap exif pcntl zip pdo_mysql bcmath

RUN pecl install -o -f redis && docker-php-ext-enable redis

# Install composer and add its bin to the PATH.
RUN curl -s http://getcomposer.org/installer | php && \
    echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc && \
    mv composer.phar /usr/local/bin/composer

# Source the bash
RUN . ~/.bashrc

#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------

ADD ./php/local.ini /usr/local/etc/php/conf.d

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

WORKDIR /var/www

COPY ./php/docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
RUN ln -s /usr/local/bin/docker-entrypoint.sh /
ENTRYPOINT ["docker-entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]
