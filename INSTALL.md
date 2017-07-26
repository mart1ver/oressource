# Installation du serveur

Instalation sous linux (debian/ubuntu)

## Dépendances

```shell
sudo apt-get install mysql-server apache2 php7.0-mysql libapache2-mod-php7.0 git
```

Note: Il est aussi possible d'utiliser `php7.0-fpm` ou d'utiliser un autre
serveur web tel que `ngnix`. De même `mariadb` remplace très bien `mysql`.

Ou bien d'utiliser `php5`.

## Clone du projet

```shell
cd ~
git clone http://github.com/mart1ver/oressource.git
```

## MariaDB/MySQL

### Créer un utilisateur

Créer l'utilisateur oressource

```shell
mysql --user=root --host=localhost -e \
  'CREATE USER 'oressource'@'localhost' IDENTIFIED BY 'mot_de_passe_a_changer'; \
  GRANT ALL PRIVILEGES ON oressource.* TO 'oressource'@'localhost' ; \
  CREATE DATABASE oressource;'
```

### Charger les données

```bash
mysql --user=oressource --host=localhost --password=mot_de_passe_a_changer \
  --database=oressource < mysql/oressource.sql
```

## Apache

### Préparer le virtual host

```shell
sudo cp -pr oressource/ /var/www/oressource
sudo chown -R www-data.www-data /var/www/oressource
```

Editer le fichier de configuration

```shell
cd /var/www/oressource/moteur/
sudo cp dbconfig.php.exemple dbconfig.php
sudo vi dbconfig.php
```

### Configurer le virtual host

Créer un fichier oressource.conf

`sudo vi /etc/apache2/site-available/oressource.conf`

Ajouter les lignes suivantes :

```
<VirtualHost *:80>
    ServerName      oressource.example.com
    DocumentRoot /var/www/oressource/
    ErrorLog /var/log/apache2/oressource-error.log
    CustomLog /var/log/apache2/oressource-access.log combined
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
sudo service apache2 restart
```

### Acceder pour la première foi Oressource

Si vous avez mis en place Oressource sur le même ordinateur sur le même
ordinateur que votre navigateur web, Vous pourrez avec le navigateur accedez à
l'écran de connection via l'URL :

```
http://localhost/oressource
```

Si votre installation est sur autre ordinateur qui ne dispose pas d'un nom de
domaine, accedez à Oressource à l'aide d'un ordinateur client via l'adresse:

```
http://IP_DU_SERVEUR/oressource
```

L' adresse IP du serveur peut etre obtenue à l'aide d'une simple commande
`ifconfig` ou `ip a` sur le serveur.

Il ne tient qu'à vous de configurer un adressage IP statique pour le serveur
voir méme un nom de domaine de manière à accéder simplement à Oressource à
partir de vos ordinateurs clients.

## Configuration coté client

(À venir)
