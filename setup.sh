#!/bin/bash
set -e

if [ ! -f /var/www/html/moteur/dbconfig.php ]; then
    cp /var/www/html/moteur/dbconfig.php.example /var/www/html/moteur/dbconfig.php
    echo "No config found, copying from example !"
fi

if [ ! -z $MYSQL_HOST ]; then
    echo "Storing custom mysql host in config!"
    sed -i "s/$host = 'localhost';/$host = '$MYSQL_HOST';/" /var/www/html/moteur/dbconfig.php
fi

if [ ! -z $MYSQL_PORT ]; then
    echo "Storing custom mysql port in config!"
    sed -i "s/$port = 3306;/$port = $MYSQL_PORT;/" /var/www/html/moteur/dbconfig.php
fi

if [ ! -z $MYSQL_DATABASE ]; then
    echo "Storing custom mysql database in config!"
    sed -i "s/$base = 'oressource';/$base = '$MYSQL_DATABASE';/" /var/www/html/moteur/dbconfig.php
fi

if [ ! -z $MYSQL_USER ]; then
    echo "Storing custom mysql username in config!"
    sed -i "s/$user = 'oressource';/$user = '$MYSQL_USER';/" /var/www/html/moteur/dbconfig.php
fi

if [ ! -z $MYSQL_PASSWORD ]; then
    echo "Storing custom mysql password in config!"
    sed -i "s/$pass = 'hello';/$pass = '$MYSQL_PASSWORD';/" /var/www/html/moteur/dbconfig.php
fi

# Start apache2
apache2-foreground