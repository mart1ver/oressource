#! /usr/bin/bash -x


if [ /usr/bin/dpkg --search /usr/bin/dpkg >/dev/null 2>&1 ]
then
  # Erreur : une distribution linux basée sur debian est requise
  exit
fi


## Installation des dépendances

sudo apt-get install mysql-server apache2 php7.0-mysql libapache2-mod-php7.0 git ssh npm
# Note: Il est aussi possible d'utiliser php7.0-fpm ou d'utiliser un autre
# serveur web tel que ngnix. De même mariadb remplace très bien mysql.


## Téléchargement des sources du projet

sudo su www-data
git clone https://github.com/mart1ver/oressource.git /var/www/oressource


## Configuration de MariaDB / MySQL

read -s -p "Veuillez choisir un mot de passe pour votre Base de Données Oressource: " database_password

# Création de l'utilisateur oressource
mysql --user=root --host=localhost -e \
  "CREATE USER 'oressource'@'localhost' IDENTIFIED BY '$database_password';
  GRANT ALL PRIVILEGES ON oressource.* TO 'oressource'@'localhost';
  CREATE DATABASE oressource;"

# Chargement des données
mysql --user=oressource --host=localhost --password=$database_password \
  --database=oressource < /var/www/oressource/mysql/oressource.sql

# Configuration de la Base de Données : création du fichier /var/www/oressource/moteur/dbconfig.php
cat > /var/www/oressource/moteur/dbconfig.php <<- EOM
<?php
// Changer ces valeurs selon votre configuration de systeme de base de donnée.
\$host='localhost';
\$base='oressource';
\$user='oressource';
\$pass='$database_password';

// Configuration interne de Oressource
try {
    \$bdd = new PDO("mysql:host=\$host;dbname=\$base;charset=utf8", \$user, \$pass);
} catch (PDOException \$e) {
    die('Connexion échouée : ' . \$e->getMessage());
}
EOM


## Configuration de Apache

# Configuration du virtual host : création du fichier /etc/apache2/site-available/oressource.conf
sudo cat > /etc/apache2/site-available/oressource.conf <<- EOM
<VirtualHost *:80>
    ServerName      oressource.example.com
    DocumentRoot /var/www/oressource/
    ErrorLog /var/log/apache2/oressource-error.log
    CustomLog /var/log/apache2/oressource-access.log combined
</VirtualHost>
EOM

# Activation du virtual host
sudo a2ensite oressource
sudo apache2ctl graceful

# Redémarage du service apache2
sudo service apache2 restart


## Installation des dépendances du client

cd /var/www/oressource/
npm install


## Génération d'une version de production des fichiers client

npm run build
cd -
