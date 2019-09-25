FROM php:7.2-apache

# Install PDO MySQL driver
# See https://github.com/docker-library/php/issues/62
RUN docker-php-ext-install mysqli pdo pdo_mysql &&\
    apt-get update &&\
    apt-get install -y zip mariadb-client

# copy website data
COPY . /var/www/html

# allow php to write in mysql backups folder
RUN chown www-data ./mysql

# https://stackoverflow.com/questions/16765158/date-it-is-not-safe-to-rely-on-the-systems-timezone-settings
RUN echo 'date.timezone = "Europe/Paris"' \
    > /usr/local/etc/php/conf.d/timezone.ini

EXPOSE 80

CMD ["/bin/bash", "setup.sh"]