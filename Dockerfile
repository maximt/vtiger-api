FROM php:8-fpm

RUN docker-php-ext-install mysqli pdo_mysql

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
