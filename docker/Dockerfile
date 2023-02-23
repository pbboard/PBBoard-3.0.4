FROM php:8.2-apache
COPY php.ini /usr/local/etc/php/
RUN apt-get -y update && apt-get -y upgrade
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev libmcrypt-dev \
  && docker-php-ext-install pdo_mysql mysqli gd iconv
RUN a2enmod rewrite

