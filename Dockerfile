
FROM php:5.6-apache

# Install PDO MySQL driver
# See https://github.com/docker-library/php/issues/62
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html

RUN cp /var/www/html/moteur/dbconfig.php.example /var/www/html/moteur/dbconfig.php
RUN sed -i "s/$host='localhost';/$host='database';/" /var/www/html/moteur/dbconfig.php

# https://stackoverflow.com/questions/16765158/date-it-is-not-safe-to-rely-on-the-systems-timezone-settings
RUN echo 'date.timezone = "Europe/Paris"' \
         > /usr/local/etc/php/conf.d/timezone.ini

EXPOSE 80 
