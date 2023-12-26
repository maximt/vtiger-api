FROM php:7-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN apt-get update && apt-get install -y git
RUN docker-php-ext-install mysqli pdo_mysql


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY composer.json composer.lock /var/www/html/

WORKDIR /var/www/html

RUN composer install

EXPOSE 9000

CMD ["php-fpm"]
