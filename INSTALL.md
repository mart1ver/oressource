# Installation du serveur

Instalation sous linux (Debian/Ubuntu)

## Dépendances

NOTE:

Il faut une version de MySQL >= 5.6.5 ou MariaDB >= 10.0.1.

Pour une installation avec le mod php de apache:

```shell
sudo apt-get install mysql-server apache2 php7.0-mysql libapache2-mod-php7.0 git
```

Note: Il est aussi possible d'utiliser `php7.0-fpm` ou d'utiliser un autre
serveur web tel que `ngnix`. De même `mariadb` remplace très bien `mysql`.

## Clonage du projet

```shell
cd ~
git clone http://github.com/mart1ver/oressource.git
```

## MariaDB/MySQL

### Création de l'utilisateur oressource et de la base de donnée oressource

Création de l'utilisateur oressource. Veillez à bien changer le mode de passe!

```shell
mysql --user=root --host=localhost -e \
  "CREATE USER 'oressource'@'localhost' IDENTIFIED BY 'mot_de_passe_a_changer';
  GRANT ALL PRIVILEGES ON oressource.* TO 'oressource'@'localhost';
  CREATE DATABASE oressource;"
```

### Charger les données

Le fichier `mysql/oressource.sql` contient un schéma par defaut de base de donnée pour Oressource.
Il est neccessaire de le charger pour avoir un Oressource fonctionnel.

La ligne de commande va vous demander votre mot de passe.

```shell
mysql --user=oressource --host=localhost --database=oressource -p < mysql/oressource.sql
```

## Apache

Une configuration simple et compatible avec un usage en réseau local non
exposé à internet.

### Préparer le virtual host

```shell
sudo cp -pr oressource/ /var/www/oressource
sudo chown -R www-data:www-data /var/www/oressource
```

Editer le fichier de configuration de Oressource

```shell
cd /var/www/oressource/moteur/
sudo cp dbconfig.php.example dbconfig.php
sudo vi dbconfig.php
```

### Configurer le virtual host

Créer un fichier oressource.conf

`sudo vi /etc/apache2/sites-available/oressource.conf`

Ajouter les lignes suivantes :

```xml
<VirtualHost *:80>
    ServerName    oressource.example.com
    DocumentRoot  /var/www/oressource/
    ErrorLog      /var/log/apache2/oressource-error.log
    CustomLog     /var/log/apache2/oressource-access.log combined
</VirtualHost>
```

### Activer le virtual host

```shell
sudo a2ensite oressource
sudo apache2ctl graceful
```

Un petit redemarage du service apache2 semble oportun apres toute cette
configuration.

```shell
sudo systemctl restart apache2
```

### Acceder pour la première fois Oressource

Si vous avez mis en place Oressource sur le même ordinateur sur le même
ordinateur que votre navigateur web, Vous pourrez avec le navigateur accedez à
l'écran de connection via l'URL :

<http://localhost/oressource>

Si votre installation est sur autre ordinateur qui ne dispose pas d'un nom de
domaine, accedez à Oressource à l'aide d'un ordinateur client via l'adresse:

<http://IP_DU_SERVEUR/oressource>

L' adresse IP du serveur peut etre obtenue à l'aide d'une simple commande
`ifconfig` ou `ip a` sur le serveur.

Il ne tient qu'à vous de configurer un adressage IP statique pour le serveur
voir méme un nom de domaine de manière à accéder simplement à Oressource à
partir de vos ordinateurs clients.

Vous pouvez connecter avec les identifiants par défaut:

- utilisateur: admin@oressource.org
- mot de passe: admin

## Configuration coté client

(À venir)
